<?php

namespace App\Http\Controllers;

use App\Item;
use App\ItemProjects;
use App\ControlMethod;
use App\Uom;
use App\Commodity;
use App\Type;
use App\Packing;
use App\Project;
use Illuminate\Http\Request;
use Auth;
use App\Company;

class ItemController extends Controller
{
    
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            if ( $user->hasRole('Superadmin') ) {
            }else if ( $user->hasRole('Admin') || $user->hasRole('Admin-BGR') || $user->hasRole('Admin-Client') ) {
                if (empty(session()->get('current_project'))) {
                    return redirect('empty-project');
                }
            }else if (empty(session()->get('current_project'))) {
                return redirect('empty-project');
            }

            return $next($request);
        });
       /*   */
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->hasRole('Superadmin')) {
            $collections = Item::orderBy('id', 'desc')->get();
        } else {
            $projectId = session()->get('current_project')->id;
            $collections = Item::join('item_projects', 'item_projects.item_id', 'items.id')
                                ->where('item_projects.project_id', $projectId)
                                ->orderBy('items.id', 'desc')
                                ->select(['items.*'])
                                ->get();
        }
        return view('items.index',compact('collections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $status="create";
        $action = route('items.store');
        $method = 'POST';
        $uoms = Uom::pluck('name', 'id');
        $commodities = Commodity::get(['name', 'code', 'id']);
        $control_methods = ControlMethod::pluck('name', 'id');
        if(Auth::user()->hasRole('Superadmin')) {
            $projects = Project::get(['id', 'name', 'project_id']);
        } else {
            $projects = Auth::user()->projects;
        }   

        $item = new Item;

        $types = Type::get(['name', 'id']);
        $packings = Packing::get(['name', 'id']);

        return view('items.create',compact('types', 'packings', 'projects', 'action','method','item','uoms','commodities','projects','control_methods','companies','status'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validate
        $request->validate([
            'project_id' => 'required',
            'sku' => 'required|unique:items,sku',
            'additional_reference' => 'required',
            'name' => 'required',
            'handling_tarif' => 'required|numeric',
            'length' => 'required|numeric',
            'width' => 'required|numeric',
            'height' => 'required|numeric',
            'volume' => 'required|numeric',
            'weight' => 'required|numeric',
            'min_qty' => 'nullable|numeric',
            'default_uom_id' => 'required',
            'commodity_id' => 'required',
            'type_id' => 'required',
            'packing_id' => 'required',
            'control_method_id' => 'required',
        ]);

        $data_item = array(
            'sku' => $request->get('sku'),
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'handling_tarif' => $request->get('handling_tarif'),
            'length' => $request->get('length'),
            'width' => $request->get('width'),
            'height' => $request->get('height'),
            'volume' => $request->get('volume'),
            'weight' => $request->get('weight'),
            'min_qty' => $request->get('min_qty'),
            'default_uom_id' => $request->get('default_uom_id'),
            'control_method_id' => $request->get('control_method_id'),
            'commodity_id' => $request->get('commodity_id'),
            // 'project_id' => $request->get('project_id'),
            'additional_reference' => $request->get('additional_reference'),
            'type_id' =>  $request->get('type_id'),
            'packing_id' =>  $request->get('packing_id'),
        );

        $input = array_except($data_item, '_token');
        $item = Item::create($input);

        //item has multiple project
        $item->project()->sync($request->get('project_id'));
        
        return redirect('items/'.$item->id.'/edit')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        $status="edit";
        $action = route('items.update', $item->id);
        $method = 'PUT';
        $uoms = Uom::pluck('name', 'id');
        $commodities = Commodity::get(['name', 'code', 'id']);
        $control_methods = ControlMethod::pluck('name', 'id');
        if(Auth::user()->hasRole('Superadmin')) {
            $projects = Project::get(['id', 'name', 'project_id']);
        } else {
            $projects = Auth::user()->projects;
        }  

        $item_project[$item->id] = ItemProjects::where('item_id' ,$item->id)->get();

        $types = Type::get(['name', 'id']);
        $packings = Packing::get(['name', 'id']);

        return view('items.edit',compact('types', 'packings', 'projects', 'action','method','item','uoms','commodities','control_methods','companies','item_project','status'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        //Validate
        $request->validate([
            'sku' => 'required|unique:items,sku,'.$item->id,
            'name' => 'required',
            'handling_tarif' => 'required|numeric',
            'length' => 'required|numeric',
            'width' => 'required|numeric',
            'height' => 'required|numeric',
            'volume' => 'required|numeric',
            'weight' => 'required|numeric',
            'min_qty' => 'nullable|numeric',
            'type_id' => 'required',
            'packing_id' => 'required',
            'project_id' => 'required',
            'additional_reference' => 'required',
            'default_uom_id' => 'required',
            'commodity_id' => 'required',
            'control_method_id' => 'required',
        ]);

        $data_item = array(
            'sku' => $request->get('sku'),
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'handling_tarif' => $request->get('handling_tarif'),
            'length' => $request->get('length'),
            'width' => $request->get('width'),
            'height' => $request->get('height'),
            'volume' => $request->get('volume'),
            'weight' => $request->get('weight'),
            'min_qty' => $request->get('min_qty'),
            'default_uom_id' => $request->get('default_uom_id'),
            'control_method_id' => $request->get('control_method_id'),
            'commodity_id' => $request->get('commodity_id'),
            // 'project_id' => $request->get('project_id'),
            'additional_reference' => $request->get('additional_reference'),
            'type_id' =>  $request->get('type_id'),
            'packing_id' =>  $request->get('packing_id'),
        );

        $input = array_except($data_item, '_token');
        $item->update($input);

        //item has multiple project
        $item->project()->sync($request->get('project_id'));

        return redirect('items/'.$item->id.'/edit')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        $hasRelations = FALSE;
        // if($item->stocks->count()>0) {
        //     $hasRelations = TRUE;
        // }
        if($item->advance_notice_details->count()>0) {
            $hasRelations = TRUE;
        }
        if($item->stock_transport_details->count()>0) {
            $hasRelations = TRUE;
        }
        if($item->stock_entry_details->count()>0) {
            $hasRelations = TRUE;
        }
        if($item->stock_delivery_details->count()>0) {
            $hasRelations = TRUE;
        }
        if($hasRelations) {
            return redirect('items')->with('message','Item sudah digunakan dan tidak bisa dihapus');
        } else {
            $item->delete();
            return redirect('items')->with('success','Data berhasil dihapus');
        }
    }
}
