<?php

namespace App\Http\Controllers;

use App\Contract;
use App\Commodity;
use App\Project;
use App\Warehouse;
use App\ContractWarehouse;
use Illuminate\Http\Request;
use Auth;

class ContractController extends Controller
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
            $collections = Contract::orderBy('id', 'desc')->get();
        } 
        // elseif(Auth::user()->hasRole('WarehouseManager')) {
        //     $branch_id = Auth::user()->branch_id;
        //     $warehouses = Warehouses::where('branch_id', $branch_id)->pluck('id');
        //     $projectId = session()->get('current_project')->id;
        //     $collections = Contract::leftJoin('contract_warehouse', 'contract_warehouse.id', 'contracts.id')->where('project_id', $projectId)
        //     ->whareIn('contract_warehouse.warehouse_id', $warehouses->id)
        //     ->orderBy('id', 'desc');
        // }
        else {
            $projectId = session()->get('current_project')->id;
            $collections = Contract::where('project_id', $projectId)->orderBy('id', 'desc')->get();
        }
        return view('contracts.index',compact('collections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $action = route('contracts.store');
        $method = 'POST';
        $commodities = Commodity::where('id', '<>', '24')->get(['id', 'name', 'code']);
        if(Auth::user()->hasRole('Superadmin')) {
            // Display all companies
            $projects = Project::get(['id', 'name', 'project_id']);
        } else {
            // Display current login company
            $projects = Auth::user()->projects;
        }   

        $contract = new Contract;
        return view('contracts.create',compact('contract','projects','action','method','companies', 'commodities'));
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
            'number_contract' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'space_allocated' => 'required|numeric',
            'tariff_space' => 'required|numeric',
            'tariff_handling_in' => 'required|numeric',
            'tariff_handling_out' => 'required|numeric',
            'commodity_id' => 'required'
        ]);

        $model = new Contract;

        if($request->hasFile('contract_doc')) {
            $model->contract_doc = $request->contract_doc->store('contract', 'public');
        }

        $model->number_contract = $request->get('number_contract');
        $model->start_date = $request->get('start_date');
        $model->end_date = $request->get('end_date');
        $model->charge_method = $request->get('charge_method');
        $model->charge_space = $request->get('charge_space');
        $model->space_allocated = $request->get('space_allocated');
        $model->project_id = $request->get('project_id');
        $model->unit_space = $request->get('unit_space');
        $model->tariff_space = $request->get('tariff_space');
        $model->unit_handling_in = $request->get('unit_handling_in');
        $model->tariff_handling_in = $request->get('tariff_handling_in');
        $model->unit_handling_out = $request->get('unit_handling_out');
        $model->tariff_handling_out = $request->get('tariff_handling_out');
        $model->is_active = $request->get('is_active');
        $model->commodity_id = $request->get('commodity_id');
        $model->save();
        
        return redirect('contracts/'.$model->id.'/edit')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Contract $contract)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Contract $contract)
    {
        $action = route('contracts.update', $contract->id);
        $method = 'PUT';
        $warehouses = $contract->warehouses->pluck('name','id');
        $commodities = Commodity::where('id', '<>', '24')->get(['id', 'name', 'code']);
        if(Auth::user()->hasRole('Superadmin')) {
            // Display all companies
            $projects = Project::get(['id', 'name', 'project_id']);
        } else {
            // Display current login company
            $projects = Auth::user()->projects;
        }  

        foreach ($contract->warehouses as $warehouse) {
            $warehouse->space = ContractWarehouse::where([
                    'contract_id' => $contract->id,
                    'warehouse_id' => $warehouse->id,
                ])->first(['rented_space'])->rented_space;
        }
        return view('contracts.edit',compact('projects','action','method','contract','companies','warehouses', 'commodities'));
    }

    public function edit_warehouses(Contract $contract)
    {
        $action = route('contracts.update_warehouses', $contract->id);
        $method = "PUT";
        $warehouses = Warehouse::all();

        foreach ($warehouses as $warehouse) {
            $contractWarehouse = ContractWarehouse::where([
                    'contract_id' => $contract->id,
                    'warehouse_id' => $warehouse->id,
                ])->first();
            $warehouse->selected = !empty($contractWarehouse);
            $warehouse->space = empty($contractWarehouse) ? '': $contractWarehouse->rented_space; 
        }
        
        return view('contracts.warehouses',compact('action','method','contract','warehouses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contract $contract)
    {
        // dd($request->commodity_id);
        //Validate
        $request->validate([
            'number_contract' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'space_allocated' => 'required|numeric',
            'tariff_space' => 'required|numeric',
            'tariff_handling_in' => 'required|numeric',
            'tariff_handling_out' => 'required|numeric',
            'commodity_id' => 'required'
        ]);

        $model = $contract;

        if($request->hasFile('contract_doc')) {
            $model->contract_doc = $request->contract_doc->store('contract', 'public');
        }

        //return $request->get('unit_space');

        
        $model->number_contract = $request->get('number_contract');
        $model->start_date = $request->get('start_date');
        $model->end_date = $request->get('end_date');
        $model->charge_method = $request->get('charge_method');
        $model->charge_space = $request->get('charge_space');
        $model->space_allocated = $request->get('space_allocated');
        $model->project_id = $request->get('project_id');
        $model->unit_space = $request->get('unit_space');
        $model->tariff_space = $request->get('tariff_space');
        $model->unit_handling_in = $request->get('unit_handling_in');
        $model->tariff_handling_in = $request->get('tariff_handling_in');
        $model->unit_handling_out = $request->get('unit_handling_out');
        $model->tariff_handling_out = $request->get('tariff_handling_out');
        $model->is_active = $request->get('is_active');
        $model->commodity_id = $request->commodity_id;
        $model->save();
        // dd($model);
        return redirect('contracts/'.$model->id.'/edit')->with('success', 'Data berhasil disimpan');
    }

    public function update_warehouses(Request $request, Contract $contract)
    {
        //Validate
        $request->validate([
            //'warehouses' => 'required|present|filled',
        ]);

        $warehouses = $request->get('warehouses');
        $warehouseSpaces = $request->get('warehouses_space');
        // dd($request);
        $model = $contract;
        
        ContractWarehouse::where('contract_id', $contract->id)->delete();

        if($warehouses){
            foreach ($warehouses as $key => $value) {
                $contractWarehouse = new ContractWarehouse;
                $contractWarehouse->contract_id = $contract->id;
                $contractWarehouse->warehouse_id = $value;
                $contractWarehouse->rented_space = $warehouseSpaces[$value];
                $contractWarehouse->save();
            }
        }
        

        // Return edit user model
        return redirect()->route('contracts.edit',$model->id)->with('success', 'Data berhasil disimpan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contract $contract)
    {
        $contract->spks()->delete();
        $contract->delete();
        return redirect('contracts')->with('success','Data berhasil dihapus');
    }
}
