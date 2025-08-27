<?php

namespace App\Http\Controllers;

use App\Storage;
use App\StorageProjects;
use App\Rules\StorageWarehouseValueRule;
use App\Rules\StorageCodeValidationRule;
use App\Commodity;
use App\Warehouse;
use App\Project;
use Illuminate\Http\Request;
use Auth;
use DB;

class StorageController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            if ( $user->hasRole('Superadmin') ) {
            } else if ( $user->hasRole('Admin') || $user->hasRole('Admin-BGR') || $user->hasRole('Admin-Client') ) {
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
            $collections = Storage::get();
        } else {
            // 1. Kalau ada data cabang, filter datanya
            if($branch = Auth::user()->branch) {
                $storages = [];
                $i = 0;
                if($branch->warehouses->count()>0) {
                    foreach ($branch->warehouses as $warehouse) {
                        if($warehouse->storages->count()>0) {
                            
                            foreach ($warehouse->storages as $storage) {
                                // dd($storage->project[0]);
                                if($storage->project->count() > 0) {
                                    if($storage->project[0]->id == session()->get('current_project')->id) {
                                        $storages[] = $storage;
                                        $i++;
                                    }
                                }
                                // dd($storage->project[0]->id);
                            }
                        }
                    }
                }
                $collections = collect($storages);
                // dd($collections);
            } else {
                $collections = Storage::get();
            }
            // dd($collections);
        }
        return view('storages.index',compact('collections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $action = route('storages.store');
        $method = 'POST';
        if(Auth::user()->hasRole('Superadmin')) {
            $warehouses = Warehouse::get();
        } else {
            // 1. Kalau ada data cabang, filter datanya
            if($branch = Auth::user()->branch) {
                $storages = [];
                if($branch->warehouses->count()>0) {
                    $warehouses = $branch->warehouses;
                }
            } else {
                $warehouses = Warehouse::get();
            }
            // dd($warehouses);
            $projects = Auth::user()->projects;
        }
        $commodities = Commodity::all();
        $storage = new Storage;
        return view('storages.create',compact('action','method','storage','warehouses','commodities','status'));
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
            'warehouse_id' => 'required|bail',
            'code' => ['required', 'min:1', $store_code = new StorageCodeValidationRule($request->warehouse_id)],
            'row' => 'required',
            'column' => 'required',
            'level' => 'required',
            'length' => 'required|numeric',
            'width' => 'required|numeric',
            'height' => 'required|numeric',
            'volume' => 'required|numeric',
            'weight' => 'required|numeric'
        ]);

        $warehouseId    = $request->get('warehouse_id');
        $length         = $request->get('length');
        $width          = $request->get('width');
        $height         = $request->get('height');
        $volume         = $request->get('volume');
        $weight         = $request->get('weight');

        $warehouse = Warehouse::find($request->get('warehouse_id'));
        // storage value can't be greater than warehouse
        $request->validate([
            'width' => new StorageWarehouseValueRule($warehouse),
            'height' => new StorageWarehouseValueRule($warehouse, 'tall'),
            'length' => new StorageWarehouseValueRule($warehouse),
            'volume' => new StorageWarehouseValueRule($warehouse, 'total_volume'),
            'weight' => new StorageWarehouseValueRule($warehouse, 'total_weight'),
        ]);

        if($request->has('is_quarantine')) {
            $is_quarantine = 1;
        }
        else{
            $is_quarantine = 2;
        }

        $data_storage = array(
            'row' => $request->get('row'),
            'column' => $request->get('column'),
            'level' => $request->get('level'),
            'length' => $length,
            'width' => $width,
            'height' => $height,
            'volume' => $volume,
            'weight' => $weight,
            'is_active' => $request->get('is_active'),
            'is_quarantine' => $is_quarantine,
            'status' => $request->get('status'),
            'warehouse_id' => $warehouse->id,
            'commodity_id' => $request->get('commodity_id'),
            'code' => $request->get('code'),
        );

        $input = array_except($data_storage, '_token');
        $storage = Storage::create($input);
        
        return redirect()->route('storages.index')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Storage  $storage
     * @return \Illuminate\Http\Response
     */
    public function show(Storage $storage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Storage  $storage
     * @return \Illuminate\Http\Response
     */
    public function edit(Storage $storage)
    {
        $action = route('storages.update', $storage->id);
        $method = "PUT";
        $id_project = null;
        if(Auth::user()->hasRole('Superadmin')) {
            $warehouses = Warehouse::get();
        } else {
            // 1. Kalau ada data cabang, filter datanya
            if($branch = Auth::user()->branch) {
                $storages = [];
                if($branch->warehouses->count()>0) {
                    $warehouses = $branch->warehouses;
                }
            } else {
                $warehouses = Warehouse::get();
            }
            // dd($warehouses);
        }
        $commodities = Commodity::all();
        
        return view('storages.edit',compact('action','method','storage','warehouses','commodities'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Storage  $storage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Storage $storage)
    {
        //Validate
        $request->validate([
            'warehouse_id' => 'required|bail',
            'code' => ['required', 'min:1', $store_code = new StorageCodeValidationRule($request->warehouse_id, $storage->id)],
            'row' => 'required',
            'column' => 'required',
            'level' => 'required',
            'length' => 'required|numeric',
            'width' => 'required|numeric',
            'height' => 'required|numeric',
            'volume' => 'required|numeric',
            'weight' => 'required|numeric'
        ]);

        $warehouse = Warehouse::find($request->get('warehouse_id'));

        $length         = $request->get('length');
        $width          = $request->get('width');
        $height         = $request->get('height');
        $volume         = $request->get('volume');
        $weight         = $request->get('weight');

        $request->validate([
            'width' => new StorageWarehouseValueRule($warehouse),
            'height' => new StorageWarehouseValueRule($warehouse, 'tall'),
            'length' => new StorageWarehouseValueRule($warehouse),
            'volume' => new StorageWarehouseValueRule($warehouse, 'total_volume'),
            'weight' => new StorageWarehouseValueRule($warehouse, 'total_weight'),
        ]);

        if($request->has('is_quarantine')) {
            $is_quarantine = 1;
        }
        else{
            $is_quarantine = 2;
        }

        $data_storage = array(
            'row' => $request->get('row'),
            'column' => $request->get('column'),
            'level' => $request->get('level'),
            'length' => $length,
            'width' => $width,
            'height' => $height,
            'volume' => $volume,
            'weight' => $weight,
            'is_active' => $request->get('is_active'),
            'is_quarantine' => $is_quarantine,
            'status' => $request->get('status'),
            'warehouse_id' => $warehouse->id,
            'commodity_id' => $request->get('commodity_id'),
            'code' => $request->get('code'),
        );

        $input = array_except($data_storage, '_token');
        $storage->update($input);

        return redirect()->route('storages.index')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Storage  $storage
     * @return \Illuminate\Http\Response
     */
    public function destroy(Storage $storage)
    {
        if (!empty($storage->stock_entry_detail)) {
            if($storage->stock_entry_detail->count()>0){
                return redirect('storages')->with('message','Data tidak bisa dihapus');
            } 
        }
        
        $storage->delete();
        return redirect('storages')->with('success','Data berhasil dihapus');
        
    }

    public function stocks()
    {
        $collections = Storage::get();
        return view('storages.stock',compact('collections'));
    }

    public function entries(Storage $storage)
    {
        return view('storages.entries',compact('storage'));
    }

    public function change_storage_status(Request $request)
    {
        $storage = Storage::find($request->input('storage_id'));
        $status = $request->input('status');
        $type = $request->input('type');

        $storage->status = $status;
        $storage->save();

        return redirect('storages')->with('success', 'Storage berhasil ditutup');
    }

    
}
