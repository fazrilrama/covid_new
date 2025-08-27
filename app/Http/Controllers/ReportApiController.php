<?php

namespace App\Http\Controllers;

use DB;
use Form;
use Yajra\DataTables\DataTables;
use App\Contract;
use App\ContractWarehouse;
use App\StockTransportDetail;
use App\StockDeliveryDetail;
use App\Warehouse;
use App\StockEntryDetail;
use App\StockDelivery;
use Auth;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportExport;


class ReportApiController extends Controller
{
    public $stock = 0;
    // class for API
    /**
     * @return mixed
     */

    public function dataTablesStockOnLocation(Request $request)
    {  
        set_time_limit(0);
        $collection = [];
        $storages = \App\Storage::get();
        $projectId = session()->get('current_project')->id;

        $data['sku'] = $request->input('sku');
        $data['control_date'] = $request->input('control_date');
        $data['ref'] = $request->input('ref');
        $data['storage_id'] = $request->input('storage_id');
        $submit = $request->input('submit');
        $user = \App\User::find(Auth::user()->id);
        // dd($request);
        $warehouses = \App\Storage::join('storage_projects as sp','sp.storage_id','=','storages.id')
            ->where('sp.project_id', $projectId);

        $masterData = \App\StockEntryDetail::join('stock_entries as se','se.id','=',
            'stock_entry_details.stock_entry_id')
            ->where('se.project_id', $projectId)
            ->where('se.status','Completed');
            //return $warehouses;
	
        if ($user->hasRole('WarehouseSupervisor')) {
            $warehouses = $warehouses->join('warehouses as w','w.id','=','storages.warehouse_id')
                        ->where('w.branch_id', $user->branch_id)
                        ->where('w.id', session()->get('warehouse_id'))
                        ->groupBy('storages.warehouse_id')
                        ->get(['storages.warehouse_id']);
            
            $skus = (clone $masterData)
                            ->where('se.warehouse_id', session()->get('warehouse_id'))
                            ->where('se.type', 'inbound')
                            ->groupBy('stock_entry_details.item_id')
                            ->get(['stock_entry_details.item_id']);  

            $ref_codes =  (clone $masterData)
                            ->where('se.warehouse_id', session()->get('warehouse_id'))
                            ->where('se.type', 'inbound')
                            ->groupBy('stock_entry_details.ref_code')
                            ->pluck('stock_entry_details.ref_code');

            $control_dates = (clone $masterData)
                                ->where('se.type', 'inbound')
                                ->groupBy('stock_entry_details.control_date')
                                ->pluck('stock_entry_details.control_date');  

            // if(empty($data['sku'])){
            //     $data['sku'] = $skus->first()->item_id;
            // }
                   $collections = (clone $masterData)
                ->where('se.warehouse_id', session()->get('warehouse_id'))
                ->select(['stock_entry_details.item_id','stock_entry_details.ref_code','stock_entry_details.control_date','stock_entry_details.storage_id','stock_entry_details.qty','se.type','se.code','se.id as se_id']);
                if($data['ref'] != 0 || $data['ref'] != null) {
                    
                    $collections->where('stock_entry_details.ref_code',$data['ref']);
                }
                $collections = $collections->get();
        } elseif($user->hasRole('WarehouseManager')) {
	    
            $manager_warehouse = \App\Warehouse::where('branch_id', $user->branch_id)->pluck('id');
		
            $warehouses = $warehouses
                ->whereIn('warehouse_id', $manager_warehouse)
                ->groupBy('storages.warehouse_id')
                ->get(['storages.warehouse_id']);
            
            $skus = (clone $masterData)
                ->whereIn('se.warehouse_id', $manager_warehouse)
                ->where('se.type', 'inbound')
                ->groupBy('stock_entry_details.item_id')
                ->get(['stock_entry_details.item_id']);  
            
            $ref_codes = (clone $masterData)
                ->whereIn('se.warehouse_id', $manager_warehouse)
                ->where('se.type', 'inbound')
                ->groupBy('stock_entry_details.ref_code')
                ->pluck('stock_entry_details.ref_code');  
            
            $control_dates = (clone $masterData)
                ->whereIn('se.warehouse_id', $manager_warehouse)
                ->where('se.type', 'inbound')
                ->groupBy('stock_entry_details.control_date')
                ->pluck('stock_entry_details.control_date');  
            if(empty($data['ref'])){
                $data['ref'] = $ref_codes->first();
            }  
            $collections = (clone $masterData)
                ->whereIn('se.warehouse_id', $manager_warehouse)
                ->where('stock_entry_details.ref_code',$data['ref'])
                ->select(['stock_entry_details.item_id','stock_entry_details.ref_code','stock_entry_details.control_date','stock_entry_details.storage_id','stock_entry_details.qty','se.type','se.code','se.id as se_id'])->get();
        } elseif($user->hasRole('CargoOwner') || $user->hasRole('Admin-BGR')) {
            $warehouses = $warehouses
                ->where('sp.project_id', $projectId)
                ->groupBy('storages.warehouse_id')
                ->get(['storages.warehouse_id']);

            $skus = (clone $masterData)
                ->where('se.type', 'inbound')
                ->groupBy('stock_entry_details.item_id')
                ->get(['stock_entry_details.item_id']); 
            
            $ref_codes = (clone $masterData)
                ->where('se.type', 'inbound')
                ->groupBy('stock_entry_details.ref_code')
                ->pluck('stock_entry_details.ref_code'); 
            
            $control_dates = (clone $masterData)
                ->where('se.type', 'inbound')
                ->groupBy('stock_entry_details.control_date')
                ->pluck('stock_entry_details.control_date');
            if(empty($data['ref'])){
                $data['ref'] = $ref_codes->first();
            } 
            
            $collections = (clone $masterData)
            ->where('stock_entry_details.ref_code',$data['ref'])
            ->select(['stock_entry_details.item_id','stock_entry_details.ref_code','stock_entry_details.control_date','stock_entry_details.storage_id','stock_entry_details.qty','se.type','se.code','se.id as se_id', 'se.created_at']) 
            ->get();
        }

        foreach($warehouses as $warehouse){
            $warehouse->storages = \App\Storage::where('warehouse_id', $warehouse->warehouse_id)
                        ->groupBy('id')
                        ->get(['code','id']);
        }

        empty($data['ref']) ?: $collections = $collections->where('ref_code', $data['ref']);
        empty($data['sku']) ?: $collections = $collections->where('item_id', $data['sku']);
        empty($data['control_date']) ?: $collections = $collections->where('control_date', $data['control_date']);
        empty($data['storage_id']) ?: $collections = $collections->where('storage_id', $data['storage_id']);
        $total_inbound = 0;
        $total_outbound = 0;
        // $arr = [];       
        // dd($collections);
        foreach($collections as $collection){
            if($collection->type == 'outbound'){

                $stockDelivery = \App\StockDelivery::where('stock_entry_id', $collection->se_id)->select('status')->first();
                if($stockDelivery){
                    if($stockDelivery->status == 'Completed'){
                        $collection->type_stock = 'out';
                    }
                    else{
                        $collection->type_stock = 'saout';
                    }
                }
                else{
                    $collection->type_stock = 'saout';
                }
                $total_outbound += $collection->qty;
            }
            elseif($collection->type == 'inbound'){
                $collection->type_stock = 'in';
                $total_inbound += $collection->qty;
            }
        }

        // $collections = $collections->filter(function ($item) {
        //     return $item->type_stock != "saout";
        // });

        // dd($collections);
        // $collections = $collections->filter(function ($item) {
        //     return $item->type_stock != "saout";
        // });
        
        //return $collections;

        // Filter data collection
        
        // dd($collections);


        $stock = $total_inbound - $total_outbound;
        $this->stock = $stock;
        try {
            if($submit == 1){
                // dd("masuk x");
                return Excel::download(new ReportExport($collections,1), 'StockAllocationList.xlsx');
            }
            else {
                // dd("masuk sini");
            return DataTables::of($collections, $total_inbound, $total_outbound, $stock)
                ->addIndexColumn()
                ->editColumn('item_id', function ($collection) {   
                    return $collection->item->sku .'/'. $collection->item->name;
                })
                ->addColumn('uom', function ($collection) {   
                    return $collection->item->default_uom->name;
                })  
                ->editColumn('storage_id', function($collection) {
                    return $collection->storage->code;
                })
                // ->editColumn('qty', function($collection) {
                //     return $collection->storage->code;
                // })
                ->addColumn('onhand', function ($collection) {
                    $html = '';
                    if($collection->type_stock == 'in'){
                        $html .= number_format($collection->qty, 2, ',', '.');
                        return $html;
                    } else {
                        $html .= number_format(0, 2, ',', '.');
                        return $html;
                    };
                })
                ->addColumn('delivered', function ($collection) {
                    $html = '';
                    if($collection->type_stock != 'in'){
                        $html .= number_format($collection->qty, 2, ',', '.');
                        return $html;
                    } else {
                        $html .= number_format(0, 2, ',', '.');
                        return $html;
                    }
                })
                ->with(['inbound' => number_format($total_inbound, 2, ',', '.'), 'outbound' => number_format($total_outbound, 2, ',', '.'), 'total_result' => number_format($stock, 2, ',', '.')])
                ->make(true);
            }
        } catch (Exception $e) {
            throw new DataTablesException($e);
        }
        
    }

    public function masterFilter()
    {   
        $user = \App\User::find(Auth::user()->id);
        $masterData = \App\StockEntryDetail::join('stock_entries as se','se.id','=',
            'stock_entry_details.stock_entry_id')
            
            ->where('se.project_id', session()->get('current_project')->id)
            ->where('se.status','Completed');
        if ($user->hasRole('WarehouseSupervisor')) {
        $skus = (clone $masterData)
            ->where('se.warehouse_id', session()->get('warehouse_id'))
            ->where('se.type', 'inbound')
            ->groupBy('stock_entry_details.item_id')->select('item_id')
            ->get();  
        $ref_codes =  (clone $masterData)
            ->where('se.warehouse_id', session()->get('warehouse_id'))
            ->where('se.type', 'inbound')
            ->groupBy('stock_entry_details.ref_code')
            ->pluck('stock_entry_details.ref_code');
        $control_dates = (clone $masterData)
            ->where('se.warehouse_id', session()->get('warehouse_id'))
            ->where('se.type', 'inbound')
            ->groupBy('stock_entry_details.control_date')
            ->pluck('stock_entry_details.control_date');  
        } elseif($user->hasRole('WarehouseManager')) {
            $manager_warehouse = \App\Warehouse::where('branch_id', $user->branch_id)->pluck('id'); 
	    
            $skus = (clone $masterData)
                ->whereIn('se.warehouse_id', $manager_warehouse)
                ->where('se.type', 'inbound')
                ->groupBy('stock_entry_details.item_id')
                ->get(['stock_entry_details.item_id']);  
            $ref_codes = (clone $masterData)
                ->whereIn('se.warehouse_id', $manager_warehouse)
                ->where('se.type', 'inbound')
                ->groupBy('stock_entry_details.ref_code')
                ->pluck('stock_entry_details.ref_code');  
            
            $control_dates = (clone $masterData)
                ->whereIn('se.warehouse_id', $manager_warehouse)
                ->where('se.type', 'inbound')
                ->groupBy('stock_entry_details.control_date')
                ->pluck('stock_entry_details.control_date'); 
        /*    
	$skus = \App\StockEntryDetail::join('stock_entries as se','se.id','=',
                            'stock_entry_details.stock_entry_id')
                            ->whereIn('se.warehouse_id', $manager_warehouse)
                            ->where('se.project_id', session()->get('current_project')->id)
                            ->where('se.type', 'inbound')
                            ->where('se.status','Completed')
                            ->groupBy('stock_entry_details.item_id')
                            ->get(['stock_entry_details.item_id']);  

            $ref_codes = \App\StockEntryDetail::join('stock_entries as se','se.id','=',
                            'stock_entry_details.stock_entry_id')
                            ->whereIn('se.warehouse_id', $manager_warehouse)
                            ->where('se.project_id', session()->get('current_project')->id)
                            ->where('se.type', 'inbound')
                            ->where('se.status','Completed')
                            ->groupBy('stock_entry_details.ref_code')
                            ->pluck('stock_entry_details.ref_code');  

            $control_dates = \App\StockEntryDetail::join('stock_entries as se','se.id','=',
                            'stock_entry_details.stock_entry_id')
                            ->whereIn('se.warehouse_id', $manager_warehouse)
                            ->where('se.project_id', session()->get('current_project')->id)
                            ->where('se.type', 'inbound')
                            ->where('se.status','Completed')
                            ->groupBy('stock_entry_details.control_date')
                            ->pluck('stock_entry_details.control_date');  */
        } elseif($user->hasRole('CargoOwner') || $user->hasRole('Admin-BGR')) {
            $skus = (clone $masterData)
                ->where('se.type', 'inbound')
                ->groupBy('stock_entry_details.item_id')
                ->get(['stock_entry_details.item_id']); 
            
            $ref_codes = (clone $masterData)
                ->where('se.type', 'inbound')
                ->groupBy('stock_entry_details.ref_code')
                ->pluck('stock_entry_details.ref_code'); 
            
            $control_dates = (clone $masterData)
                ->where('se.type', 'inbound')
                ->groupBy('stock_entry_details.control_date')
                ->pluck('stock_entry_details.control_date');
        }
        
        $data= collect();
        foreach ($skus as $sku) {
            $data->push(['id' => $sku->item_id, 'name' => $sku->item->sku.'/'.$sku->item->name]);
        }
    
        return response()->json([
            'skus' => $data,
            'ref_codes' => $ref_codes,
            'control_dates' => $control_dates
        ]);
    }

    public function projectManagement(Request $request)
    {   
        $projects = ContractWarehouse::select(['warehouses.code','warehouses.name as warehouse_name', 'provinces.name as province_name', 'parties.name as branch_name', 'projects.name as project_name', 'warehouses.ownership',DB::raw("CONCAT(warehouses.id,'_',projects.id) AS concat_id")])
        ->join('contracts', 'contracts.id', 'contract_warehouse.contract_id')
        ->join('projects', 'contracts.project_id', 'projects.id')
        ->join('warehouses', 'warehouses.id', 'contract_warehouse.warehouse_id')
        ->join('parties', 'warehouses.branch_id', 'parties.id')
        ->join('provinces', 'provinces.id', 'warehouses.region_id')
        ->where('contracts.is_active','1')
        ->where('warehouses.is_active', '1')
        ->groupBy('concat_id');

        if($request->branch != null && $request->branch != 'semua') {
            $projects->where('warehouses.branch_id', $request->branch);
        } 
        
        $projects = $projects->get();

        return ['data'=>$projects];
        
    }

    public function contractManagement(Request $request)
    {   
        $contracts = ContractWarehouse::select(['warehouses.code','warehouses.name as warehouse_name', 'provinces.name as province_name', 'parties.name as branch_name', 'projects.name as project_name', 'warehouses.ownership','warehouses.status', 'contracts.number_contract','contracts.start_date', 'contracts.end_date', 'contract_warehouse.rented_space', 'commodities.name as commodity_name',DB::raw('DATEDIFF(contracts.end_date, NOW()) as end_of_contract')])
        ->join('contracts', 'contracts.id', 'contract_warehouse.contract_id')
        ->leftJoin('commodities', 'contracts.commodity_id', 'commodities.id')
        ->join('projects', 'contracts.project_id', 'projects.id')
        ->join('warehouses', 'warehouses.id', 'contract_warehouse.warehouse_id')
        ->join('parties', 'warehouses.branch_id', 'parties.id')
        ->join('provinces', 'provinces.id', 'warehouses.region_id')
        ->where('contracts.is_active','1')
        ->where('warehouses.is_active', '1');
        if($request->branch != null && $request->branch != 'all') {
            $contracts->where('warehouses.branch_id', $request->branch);
        } 
        if($request->status != null && $request->status != 'all') {
            $contracts->where('warehouses.ownership', $request->status);
        }
        if( $request->operation != 'all' && $request->operation != 'Kosong') {
            $contracts->where('warehouses.status', $request->operation);
        } elseif($request->operation == 'Kosong') {
            $contracts->whereNull('warehouses.status');
        }
        
        $contracts = $contracts->get();
        return ['data'=>$contracts];
    }

    public function goodreceiveManagement(Request $request)
    {
    //    dd($request);
        $deliveries = StockDelivery::select(['stock_deliveries.stock_entry_id','stock_deliveries.code', 'stock_deliveries.id', 'stock_deliveries.ref_code', 'stock_deliveries.consignee_id', 'stock_deliveries.origin_id', 'stock_deliveries.destination_id', 'stock_deliveries.status', 'stock_deliveries.photo', 'stock_deliveries.received_by', 'stock_deliveries.police_number', 'stock_deliveries.driver_name', 'stock_deliveries.pic', 'stock_deliveries.date_received as updated_at', 'stock_deliveries.shipper_id', 'stock_deliveries.created_at', 'stock_deliveries.eta'])
        ->selectRaw('DATEDIFF(now(), stock_deliveries.eta) as selisih')
        ->join('stock_entries', 'stock_entries.id', 'stock_deliveries.stock_entry_id')
        ->join('stock_transports', 'stock_transports.id', 'stock_entries.stock_transport_id')
        ->join('stock_advance_notices', 'stock_advance_notices.id', 'stock_transports.advance_notice_id')
        ->whereNotIn('stock_advance_notices.advance_notice_activity_id',['10','7', '13'])
        ->whereIn('stock_entries.warehouse_id', ['892', '1385', '888'])->with([
            'stock_entry.warehouse', 'origin', 'destination',
             'consignee', 'shipper'
        ])
        ->where('stock_entries.project_id','<>', '333');     
        if($request->project_id != 'all') {
            $deliveries->where('stock_deliveries.project_id', $request->project_id);
        }
        $deliveries = $deliveries->get();
        
        return ['data'=>$deliveries];
    }

    public function goodreceiveManagementMonitoring(Request $request)
    {
    //    dd($request);
        $deliveries = StockDelivery::select(['stock_deliveries.stock_entry_id','stock_deliveries.code', 'stock_deliveries.id', 'stock_deliveries.ref_code', 'stock_deliveries.consignee_id', 'stock_deliveries.origin_id', 'stock_deliveries.destination_id', 'stock_deliveries.status', 'stock_deliveries.photo', 'stock_deliveries.received_by', 'stock_deliveries.police_number', 'stock_deliveries.driver_name', 'stock_deliveries.pic', 'stock_deliveries.date_received as updated_at', 'stock_deliveries.shipper_id', 'stock_deliveries.created_at', 'stock_deliveries.eta'])
        ->selectRaw('DATEDIFF(now(), stock_deliveries.eta) as selisih')
        ->join('stock_entries', 'stock_entries.id', 'stock_deliveries.stock_entry_id')
        ->join('stock_transports', 'stock_transports.id', 'stock_entries.stock_transport_id')
        ->join('stock_advance_notices', 'stock_advance_notices.id', 'stock_transports.advance_notice_id')
        ->whereNotIn('stock_advance_notices.advance_notice_activity_id',['10','7', '13'])
        ->where('stock_deliveries.status', 'Completed')
        ->whereIn('stock_entries.warehouse_id', ['892', '1385', '888'])
        ->where('stock_entries.project_id','<>', '333')
        ->with([
            'stock_entry.warehouse', 'origin', 'destination',
             'consignee', 'shipper'
        ]);     
        if($request->project_id != 'all') {
            $deliveries->where('stock_deliveries.project_id', $request->project_id);
        }
        $deliveries = $deliveries->get();
        
        return ['data'=>$deliveries];
    }

    public function managementStock(Request $request)
{
       // dd($request->date_to);
        // dd('xa');
        $from = $request->date_from;
        $to = $request->date_to;
        $projectId = session()->get('current_project')->id;
        // dd($from, $to);
        
        // begining
        $stock_ins =  Warehouse::join('parties', 'parties.id', 'warehouses.branch_id')
        ->join('stock_entries', 'stock_entries.warehouse_id', 'warehouses.id')
        ->join('stock_transports', 'stock_transports.id', 'stock_entries.stock_transport_id')
        ->join('stock_advance_notices', 'stock_advance_notices.id', 'stock_transports.advance_notice_id')
        ->join('projects', 'projects.id', 'stock_entries.project_id')
        ->join('stock_entry_details', 'stock_entry_details.stock_entry_id', 'stock_entries.id')
        ->join('storages', 'storages.id', 'stock_entry_details.storage_id')
        ->join('items', 'items.id', 'stock_entry_details.item_id')
        ->join('uoms', 'uoms.id', 'items.default_uom_id');
        if($projectId == '337') {
            $stock_ins->select([
                'parties.name as branch_name',
                'warehouses.name as warehouse_name',
                'warehouses.id as warehouse_id',
                'projects.name as project_name',
                'items.sku',
                'items.weight',
                'warehouses.total_weight as capacity',
                'items.name as name_sku',
                'uoms.name as uom_name',
                'items.id as item_id',
                'warehouses.ownership as status_gudang',
                'stock_entries.project_id',
                'storages.damage',
                DB::raw("sum(stock_entry_details.qty) as total_in, CONCAT(stock_entry_details.item_id,'_',warehouses.id,'_', stock_entries.project_id,'_', storages.damage) AS concat_id")
            ]);
        } else {
            $stock_ins->select([
                'parties.name as branch_name',
                'warehouses.name as warehouse_name',
                'warehouses.id as warehouse_id',
                'projects.name as project_name',
                'items.sku',
                'items.weight',
                'warehouses.total_weight as capacity',
                'items.name as name_sku',
                'uoms.name as uom_name',
                'items.id as item_id',
                'warehouses.ownership as status_gudang',
                'stock_entries.project_id',
                'storages.damage',
                DB::raw("sum(stock_entry_details.qty) as total_in, CONCAT(warehouses.id,'_',stock_entry_details.item_id ,'_', stock_entries.project_id) AS concat_id")
            ]);
        }
        $stock_ins->where('warehouses.is_active', 1)
        ->where('stock_advance_notices.advance_notice_activity_id', '<>',20)
        ->where('stock_entries.project_id',  $projectId)
        // ->where(DB::raw('Date(stock_entry_details.created_at)'),'>=' ,$request->date_from)
        // ->where(DB::raw('Date(stock_entry_details.created_at)'),'<=' ,$requet->date_to)
        ->where(DB::raw('Date(stock_entry_details.updated_at)'),'<=', $to)
        ->where('stock_entries.status', 'Completed')
        ->where('stock_entries.type', 'inbound');
        if($request->warehouse_id != 'all') {
            $stock_ins
            ->where('stock_entries.warehouse_id', $request->warehouse_id);
        }
        if(Auth::user()->hasRole('WarehouseManager') && $request->warehouse_id == 'all') {
            $warehouses = Warehouse::where('branch_id', Auth::user()->branch_id)->pluck('id');
            // dd($warehouses);
            $stock_ins
                ->whereIn('stock_entries.warehouse_id', $warehouses);
        }
        $stock_ins = $stock_ins->groupBy('concat_id')
        ->get();
        // dd($stock_ins);
        
        // dd($stock_ins);
        $stock_in_after_begining = StockEntryDetail::join('stock_entries', 'stock_entries.id', 'stock_entry_details.stock_entry_id')
        ->join('stock_transports', 'stock_transports.id', 'stock_entries.stock_transport_id')
        ->join('stock_advance_notices', 'stock_advance_notices.id', 'stock_transports.advance_notice_id')
        ->join('storages', 'storages.id', 'stock_entry_details.storage_id');
        if($projectId == '337') {
            $stock_in_after_begining->select([
                DB::raw("sum(stock_entry_details.qty) as total_in, CONCAT(stock_entry_details.warehouse_id,'_',stock_entry_details.item_id,'_', stock_entries.project_id,'_', storages.damage) AS m_concat_id"), 'stock_entry_details.item_id'
                , 'stock_entry_details.warehouse_id', 'stock_entries.project_id', 'storages.damage'
            ]);
        }else {
            $stock_in_after_begining->select([
                DB::raw("sum(stock_entry_details.qty) as total_in, CONCAT(stock_entry_details.warehouse_id,'_',stock_entry_details.item_id,'_', stock_entries.project_id) AS m_concat_id"), 'stock_entry_details.item_id'
                , 'stock_entry_details.warehouse_id', 'stock_entries.project_id', 'storages.damage'
            ]);
        }
        $stock_in_after_begining = $stock_in_after_begining->where('stock_entries.status', 'Completed')
        ->where('stock_entries.type', 'inbound')
        ->where('stock_advance_notices.advance_notice_activity_id', '<>',20)
        ->where('stock_entries.project_id',  $projectId)
        ->whereBetween(DB::raw('Date(stock_entry_details.updated_at)'),[$from, $to])
        ->groupBy('m_concat_id')->get();
        
        // dd($stock_in_after_begining);
        
        $stock_out_after_begining = StockEntryDetail::join('stock_entries', 'stock_entries.id', 'stock_entry_details.stock_entry_id')
        ->join('stock_transports', 'stock_transports.id', 'stock_entries.stock_transport_id')
        ->join('stock_advance_notices', 'stock_advance_notices.id', 'stock_transports.advance_notice_id')
        ->join('storages', 'storages.id', 'stock_entry_details.storage_id');

        if($projectId == '337') {
            $stock_out_after_begining->select([
                DB::raw("sum(stock_entry_details.qty) as total_out, CONCAT(stock_entry_details.warehouse_id,'_',stock_entry_details.item_id,'_', stock_entries.project_id,'_', storages.damage) AS m_concat_id"), 'stock_entry_details.item_id'
                , 'stock_entry_details.warehouse_id', 'stock_entries.project_id', 'storages.damage','stock_entry_details.storage_id'
            ]);
        }else {
            $stock_out_after_begining->select([
                DB::raw("sum(stock_entry_details.qty) as total_out, CONCAT(stock_entry_details.warehouse_id,'_',stock_entry_details.item_id,'_', stock_entries.project_id) AS m_concat_id"), 'stock_entry_details.item_id'
                , 'stock_entry_details.warehouse_id', 'stock_entries.project_id', 'storages.damage'
            ]);
        }
        $stock_out_after_begining = $stock_out_after_begining->where('stock_entries.status', 'Completed')
        ->where('stock_entries.type', 'outbound')
        ->where('stock_advance_notices.advance_notice_activity_id', '<>',20)
        ->where('stock_entries.project_id',  $projectId)
        ->whereBetween(DB::raw('Date(stock_entry_details.updated_at)'), [$from, $to])
        ->groupBy('m_concat_id')->get();

        // dd($stock_out_after_begining);

        $stock_outs = StockEntryDetail::join('stock_entries', 'stock_entries.id', 'stock_entry_details.stock_entry_id')
        ->join('stock_transports', 'stock_transports.id', 'stock_entries.stock_transport_id')
        ->join('stock_advance_notices', 'stock_advance_notices.id', 'stock_transports.advance_notice_id')
        ->join('storages', 'storages.id', 'stock_entry_details.storage_id');

        if($projectId == '337') {
            $stock_outs->select([
                DB::raw("sum(stock_entry_details.qty) as total_out, CONCAT(stock_entry_details.warehouse_id,'_',stock_entry_details.item_id,'_', stock_entries.project_id, '_', storages.damage) AS m_concat_id"), 'stock_entry_details.item_id'
                , 'stock_entry_details.warehouse_id', 'stock_entries.project_id', 'storages.damage','stock_entry_details.storage_id'
            ]);
        }else {
            $stock_outs->select([
                DB::raw("sum(stock_entry_details.qty) as total_out, CONCAT(stock_entry_details.warehouse_id,'_',stock_entry_details.item_id,'_', stock_entries.project_id) AS m_concat_id"), 'stock_entry_details.item_id'
                , 'stock_entry_details.warehouse_id', 'stock_entries.project_id', 'storages.damage'
            ]);
        }
        $stock_outs
        ->where('stock_entries.status', 'Completed')
        ->where('stock_entries.type', 'outbound')
        ->where('stock_advance_notices.advance_notice_activity_id', '<>',20)
        ->where('stock_entries.project_id',  $projectId)
        ->where(DB::raw('Date(stock_entry_details.updated_at)'),'<=', $to);

        if($request->warehouse_id != 'all') {
            $stock_outs
            ->where('stock_entry_details.warehouse_id', $request->warehouse_id);
        }
        $stock_outs = $stock_outs->groupBy('m_concat_id')->get();
        // dd($stock_outs);

        $arr_out = [];
        foreach ($stock_outs as $out) {
            if($projectId == '337') {
                $arr_out[$out->item_id."_".$out->warehouse_id."_".$out->project_id."_".$out->damage]=$out->total_out;
            } else {
                $arr_out[$out->item_id."_".$out->warehouse_id."_".$out->project_id]=$out->total_out;
            }
        }
        // dd($arr_out);
        $arr_in_after_begining = [];
        foreach ($stock_in_after_begining as $in_after) {
            if($projectId == '337') {
                $arr_in_after_begining[$in_after->item_id."_".$in_after->warehouse_id."_".$in_after->project_id."_".$in_after->damage]=$in_after->total_in;
            } else {
                $arr_in_after_begining[$in_after->item_id."_".$in_after->warehouse_id."_".$in_after->project_id]=$in_after->total_in;
            }
        }
        // dd($arr_in_after_begining);
        $arr_out_after_begining = [];
        foreach ($stock_out_after_begining as $out_after) {
            if($projectId == '337') {
                $arr_out_after_begining[$out_after->item_id."_".$out_after->warehouse_id."_".$out_after->project_id."_".$out_after->damage]=$out_after->total_out;
            }else{
                $arr_out_after_begining[$out_after->item_id."_".$out_after->warehouse_id."_".$out_after->project_id]=$out_after->total_out;
            }
        }
        // dd($arr_out_after_begining);

        $arr_by_gudang = [];
        $fix_arr = [];
        // return response()->json($arr_in_after_begining);
        foreach ($stock_ins as $in) {
            // dd($in);
            if($projectId == '337') {
                if (!empty($arr_out[$in->item_id."_".$in->warehouse_id."_".$in->project_id."_".$in->damage])){
                    // dd('sni');
                    $qty_out = $arr_out[$in->item_id."_".$in->warehouse_id."_".$in->project_id."_".$in->damage];
                }else{
                    $qty_out = 0;
                }
                if (!empty($arr_in_after_begining[$in->item_id."_".$in->warehouse_id."_".$in->project_id."_".$in->damage])){
                    $qty_in_after = $arr_in_after_begining[$in->item_id."_".$in->warehouse_id."_".$in->project_id."_".$in->damage];
                }else{
                    $qty_in_after = 0;
                }
                // dd($qty_in_after);

                if (!empty($arr_out_after_begining[$in->item_id."_".$in->warehouse_id."_".$in->project_id."_".$in->damage])){
                    $qty_out_after = $arr_out_after_begining[$in->item_id."_".$in->warehouse_id."_".$in->project_id."_".$in->damage];
                }else{
                    $qty_out_after = 0;
                }
            } else {
                if (!empty($arr_out[$in->item_id."_".$in->warehouse_id."_".$in->project_id])){
                    $qty_out = $arr_out[$in->item_id."_".$in->warehouse_id."_".$in->project_id];
                }else{
                    $qty_out = 0;
                }
                if (!empty($arr_in_after_begining[$in->item_id."_".$in->warehouse_id."_".$in->project_id])){
                    $qty_in_after = $arr_in_after_begining[$in->item_id."_".$in->warehouse_id."_".$in->project_id];
                }else{
                    $qty_in_after = 0;
                }
                // dd($qty_in_after);

                if (!empty($arr_out_after_begining[$in->item_id."_".$in->warehouse_id."_".$in->project_id])){
                    $qty_out_after = $arr_out_after_begining[$in->item_id."_".$in->warehouse_id."_".$in->project_id];
                }else{
                    $qty_out_after = 0;
                }
            }
            // dd($qty_out_after);
            $total_out = $qty_out - $qty_out_after;

            $begining = ($in->total_in - $qty_in_after) - (($qty_out)- $qty_out_after);
            
            $stock = $in->total_in - $qty_out;
           
            $after_begining_in = $qty_in_after;
            $after_begining_out = $qty_out_after;
        //   dd($total_out);
            $utilitas = round((($stock/$in->capacity)*100),2);
            // if($stock != 0 && $stock > 0 ) {
            $kondisi = $in->damage == 1 ? ' (DAMAGE)' : '';
            if($projectId == '337') {
                $a = [
                'branch' => $in->branch_name,
                'warehouse' => $in->warehouse_name,
                'project'   => $in->project_name,
                'sku'    =>$in->sku,
                'sku_name' => $in->name_sku . ' '. $kondisi,
                'uom_name' => $in->uom_name,
                'begining' => number_format($begining,2),
                'after_begining_in' => number_format($after_begining_in,2),
                'after_begining_out' => number_format($after_begining_out,2),
                'stock' => number_format(round($stock),2)
                ];
            } else {
                $a = [
                'branch' => $in->branch_name,
                'warehouse' => $in->warehouse_name,
                'project'   => $in->project_name,
                'sku'    =>$in->sku,
                'sku_name' => $in->name_sku . ' '. $kondisi,
                'uom_name' => $in->uom_name,
                'begining' => number_format($begining,2),
                'after_begining_in' => number_format($after_begining_in,2),
                'after_begining_out' => number_format($after_begining_out,2),
                'stock' => number_format(round($stock),2)
                ]; 
            }
            if($begining > 0 || $after_begining_in > 0 || $after_begining_out > 0 || $stock > 0) {
                array_push($fix_arr, $a);
            }
            // }
            //   $fix_arr->push($a);
              
        }
        // dd($qty_out);


        return ['data' => $fix_arr];
    }

    public function mutationOnLoction(Request $request)
    {
       // dd($request->date_to);
        // dd('xa');
        $projectId = session()->get('current_project')->id;
        // dd($from, $to);
        // dd($request->warehouse_id);
        // begining
        $stock_ins = Warehouse::select([
            'parties.name as branch_name',
            'warehouses.name as warehouse_name',
            'warehouses.id as warehouse_id',
            'projects.name as project_name',
            'items.sku',
            'items.weight',
            'warehouses.total_weight as capacity',
            'items.name as name_sku',
            'uoms.name as uom_name',
            'items.id as item_id',
            'warehouses.ownership as status_gudang',
            'stock_entries.project_id',
            'storages.code as storage_name',
            'storages.id as storage_id',
            DB::raw("sum(stock_entry_details.qty) as total_in, CONCAT(stock_entry_details.storage_id,'_',stock_entry_details.warehouse_id,'_',stock_entry_details.item_id,'_', stock_entries.project_id) AS concat_id")
        ])
        ->join('parties', 'parties.id', 'warehouses.branch_id')
        ->join('stock_entries', 'stock_entries.warehouse_id', 'warehouses.id')
        ->join('projects', 'projects.id', 'stock_entries.project_id')
        ->join('stock_entry_details', 'stock_entry_details.stock_entry_id', 'stock_entries.id')
        ->join('items', 'items.id', 'stock_entry_details.item_id')
        ->join('uoms', 'uoms.id', 'items.default_uom_id')
        ->leftJoin('storages', 'storages.id', 'stock_entry_details.storage_id')
        ->where('warehouses.is_active', 1)
        ->where('stock_entries.project_id',  $projectId)
        // ->where(DB::raw('Date(stock_entry_details.created_at)'),'>=' ,$request->date_from)
        // ->where(DB::raw('Date(stock_entry_details.created_at)'),'<=' ,$requet->date_to)
        // ->where(DB::raw('Date(stock_entry_details.updated_at) <= NOW()'))
        ->where('stock_entries.status', 'Completed')
        ->where('stock_entries.type', 'inbound');
        if($request->warehouse_id != 'all') {
            $stock_ins
            ->where('stock_entries.warehouse_id', $request->warehouse_id);
        }
        if(Auth::user()->hasRole('WarehouseManager') && $request->warehouse_id == 'all') {
            $warehouses = Warehouse::where('branch_id', Auth::user()->branch_id)->pluck('id');
            // dd($warehouses);
            $stock_ins
                ->whereIn('stock_entries.warehouse_id', $warehouses);
        }
        if($request->item != 'all') {
            $stock_ins
            ->where('stock_entry_details.item_id', $request->item);
        }
        if($request->storage != 'all') {
            $stock_ins
            ->where('stock_entry_details.storage_id', $request->storage);
        }
        $stock_ins = $stock_ins->groupBy('concat_id')
        ->get();

        $stock_outs = StockEntryDetail::select([
            DB::raw("sum(stock_entry_details.qty) as total_out, CONCAT(stock_entry_details.storage_id,'_',stock_entry_details.warehouse_id,'_',stock_entry_details.item_id,'_', stock_entries.project_id) AS m_concat_id"), 'stock_entry_details.item_id'
            , 'stock_entry_details.warehouse_id', 'stock_entries.project_id', 'storages.code','storages.id as storage_id'
        ])
        ->join('stock_entries', 'stock_entries.id', 'stock_entry_details.stock_entry_id')
        ->leftJoin('storages', 'storages.id', 'stock_entry_details.storage_id')
        ->where('stock_entries.status', 'Completed')
        ->where('stock_entries.type', 'outbound')
        ->where('stock_entries.project_id',  $projectId);

        if($request->warehouse_id != 'all') {
            $stock_outs
            ->where('stock_entry_details.warehouse_id', $request->warehouse_id);
        }
        if($request->item != 'all') {
            $stock_outs
            ->where('stock_entry_details.item_id', $request->item);
        }
        if($request->storage != 'all') {
            $stock_outs
            ->where('stock_entry_details.storage_id', $request->storage);
        }
        $stock_outs = $stock_outs->groupBy('m_concat_id')->get();
        // dd($stock_outs);
        // dd($stock_ins);
        $arr_out = [];
        foreach ($stock_outs as $out) {
          $arr_out[$out->storage_id."_".$out->item_id."_".$out->warehouse_id."_".$out->project_id]=$out->total_out;
        }
        $fix_arr = [];
        foreach ($stock_ins as $in) {
            if (!empty($arr_out[$in->storage_id."_".$in->item_id."_".$in->warehouse_id."_".$in->project_id])){
                $qty_out = $arr_out[$in->storage_id."_".$in->item_id."_".$in->warehouse_id."_".$in->project_id];
            }else{
                $qty_out = 0;
            }

            $total_out = $qty_out;
            $stock = ($in->total_in) - $qty_out;
        //   dd($total_out);
            $utilitas = round((($stock/$in->capacity)*100),2);
            if($stock != 0) {
            $a = [
            'branch' => $in->branch_name,
            'warehouse' => $in->warehouse_name,
            'project'   => $in->project_name,
            'sku'    =>$in->sku,
            'storages' => $in->storage_name,
            'sku_name' => $in->name_sku,
            'status_gudang' =>$in->status_gudang,
            'capacity' => number_format(round($in->capacity),2),
            'stock' => $stock,
            'keterangan' => $stock == 0 ? 'Kosong' : 'Masih Tersedia',
            'uom_name' => $in->uom_name
            ];
            
                array_push($fix_arr, $a);
            }
            //   $fix_arr->push($a);
              
        }
        // dd($qty_out);


        return ['data' => $fix_arr];
    }

    public function detailInbound(Request $request)
    {
        $projectId = session()->get('current_project')->id;
        $from = $request->date_from;
        $to = $request->date_to;
        $type = 'inbound';
        $stocks = StockTransportDetail::select([
            'stock_transports.id',
            'stock_transport_details.updated_at as control_date',
            'parties.name as parties_name',
            'uoms.name as uom',
            'items.name as item_name',
            'stock_transports.status',
            'stock_transports.police_number',
            'stock_transports.driver_name',
            'stock_transport_details.qty',
            'stock_transports.do_number',
            'items.sku',
            'stock_transports.pic',
            'stock_transports.ref_code'
        ])->join('stock_transports', 'stock_transports.id', 'stock_transport_details.stock_transport_id')
        ->join('items', 'items.id', 'stock_transport_details.item_id')
        ->join('stock_advance_notices', 'stock_advance_notices.id', 'stock_transports.advance_notice_id')
        ->join('uoms', 'items.default_uom_id', 'uoms.id')
        ->whereBetween(DB::raw('Date(stock_transport_details.updated_at)'), [$from, $to])
        ->leftJoin('parties', 'parties.id', 'stock_transports.owner')
        ->where('stock_transports.project_id', $projectId)
        ->where('stock_transports.id', '<>', 347007)
        ->where('advance_notice_activity_id','<>', 20)
        ->where('stock_transports.type', $type);

        if($request->warehouse_id != 'all') {
            $stocks->where('stock_transports.warehouse_id', $request->warehouse_id);
        }
        if($request->item != 'all') {
            $stocks
            ->where('stock_transport_details.item_id', $request->item);
        }
        $stocks = $stocks->get();
        // dd($stocks);
        return ['data' => $stocks];
    }

    public function detailOutbound(Request $request)
    {
        $projectId = session()->get('current_project')->id;
        $from = $request->date_from;
        $to = $request->date_to;
        $type = 'inbound';
        $stocks = StockDeliveryDetail::select([
            'stock_deliveries.id',
            'stock_delivery_details.updated_at as control_date',
            'parties.name as parties_name',
            'uoms.name as uom',
            'items.name as item_name',
            'stock_deliveries.status',
            'stock_deliveries.police_number',
            'stock_deliveries.driver_name',
            'stock_delivery_details.qty',
            'stock_deliveries.do_number',
            'items.sku',
            'stock_deliveries.pic',
            'stock_deliveries.ref_code',
            'a.name as consignee_name'
        ])->join('stock_deliveries', 'stock_deliveries.id', 'stock_delivery_details.stock_delivery_id')
        ->join('items', 'items.id', 'stock_delivery_details.item_id')
        ->join('parties as a', 'a.id', 'stock_deliveries.consignee_id')
        ->join('stock_entries', 'stock_entries.id', 'stock_deliveries.stock_entry_id')
        ->join('uoms', 'items.default_uom_id', 'uoms.id')
        ->leftJoin('parties', 'parties.id', 'stock_deliveries.owner')
        ->whereBetween(DB::raw('Date(stock_delivery_details.updated_at)'), [$from, $to])
        ->where('stock_deliveries.project_id', $projectId);

        if($request->warehouse_id != 'all') {
            $stocks->where('stock_entries.warehouse_id', $request->warehouse_id);
        }
        if($request->item != 'all') {
            $stocks
            ->where('stock_delivery_details.item_id', $request->item);
        }
        $stocks = $stocks->get();
        // dd($stocks);
        return ['data' => $stocks];
    }
}