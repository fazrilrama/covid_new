<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\WarehouseOfficer;
use App\Region;
use App\Storage;
use App\PartyType;
use App\Party;
use App\Warehouse;
use App\Item;
use App\User;
use App\Uom;
use App\StockEntry;
use App\StockEntryDetail;
use App\Project;
use App\AdvanceNotice;
use App\ItemProjects;
use DB;
use Auth;
use App\AdvanceNoticeDetail;
use App\StockTransport;
use App\StockTransportDetail;
use App\StockDelivery;
use App\StockDeliveryDetail;
use App\StockOpname;
use App\Contract;
use App\AssignedTo;
use App\ContractWarehouse;
use Carbon\CarbonPeriod;
use Carbon;
use App\Providers\DataTablesBuilderService;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AdvanceNoticeExport;
use App\Exports\ReportExport;
use App\Exports\StockTransportDetailExport;
use App\Exports\StockDeliveryDetailExport;
use App\Exports\ManagementStockExport;
use App\Exports\GoodReceiveReportExport;
use App\Exports\StockOnLocationReport;
use App\Exports\MutasiStockExport;
use App\Exports\GoodIssueMutationReportExport;
use App\Exports\GoodIssueMutationSKUReportExport;
use App\Exports\MutationMonthReportExport;


class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            if ( $user->hasRole('Superadmin') ) {
            }
            if ( $user->hasRole('Admin') || $user->hasRole('Admin-BGR') || $user->hasRole('Admin-Client') ) {
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

    public function stockOnLocation(DataTablesBuilderService $builder, Request $request)
    {

	$collection = [];
        $storages = \App\Storage::get();
        $projectId = session()->get('current_project')->id;
        $data['sku'] = $request->input('sku');
        $data['control_date'] = $request->input('control_date');
        $data['ref'] = $request->input('ref');
        $data['storage_id'] = $request->input('storage_id');
        $submit = $request->input('submit');
        $user = \App\User::find(Auth::user()->id);
        
        $warehouses = \App\Storage::join('storage_projects as sp','sp.storage_id','=','storages.id')
                            ->where('sp.project_id', $projectId);
        // dd($warehouses);
        if ($user->hasRole('WarehouseSupervisor')) {
            $warehouses = $warehouses->join('warehouses as w','w.id','=','storages.warehouse_id')
                            ->where('w.branch_id', $user->branch_id)
                            ->where('w.id', session()->get('warehouse_id'))
                            ->groupBy('storages.warehouse_id')
                            ->get(['storages.warehouse_id']);
        }
        elseif($user->hasRole('WarehouseManager')) {
            $manager_warehouse = Warehouse::where('branch_id', $user->branch_id)->pluck('id');
	    //dd($manager_warehouse);
            $warehouses = Storage::join('storage_projects as sp','sp.storage_id','=','storages.id')
                            ->whereIn('warehouse_id', $manager_warehouse)
                            ->where('sp.project_id', $projectId)
                            ->groupBy('storages.warehouse_id')
                            ->get(['storages.warehouse_id']);	   
	
        } elseif($user->hasRole('CargoOwner') || $user->hasRole('Admin-BGR')){
            $warehouses = $warehouses
                ->join('warehouses as w','w.id','=','storages.warehouse_id')
                ->where('sp.project_id', $projectId)
                ->groupBy('storages.warehouse_id')
                ->get(['storages.warehouse_id']);
        } 

        foreach($warehouses as $warehouse){
            $warehouse->storages = \App\Storage::where('warehouse_id', $warehouse->warehouse_id)
            ->groupBy('id')
            ->get(['code','id']);
	    //dd($warehouse);
        }
	
        $dataTables = $builder
            ->setOrder(0)
            ->setUrl(route('report.stock_on_location', $request->all()))
            ->withIndex()
            ->setColumns([
                [
                    'name'       => 'code',
                    'title'      => 'No Transaksi:',
                    'searchable' => false,
                ],
                [
                    'name'       => 'item_id',
                    'title'      => 'Item SKU / Name:',
                    'searchable' => false,

                ],
                [
                    'name'       => 'ref_code',
                    'title'      => 'Group Ref:',
                    'orderable'  => false,
                    'searchable' => false,
                ],
                [
                    'name'       => 'control_date',
                    'title'      => 'Control Date:',
                    'searchable' => false,
                ],
                [
                    'name'       => 'uom',
                    'title'      => 'Uom:',
                    'orderable'  => false,
                    'searchable' => false,
                ],
                [
                    'name'       => 'storage_id',
                    'title'      => 'Storage:',
                    'searchable' => false,
                ],
                [
                    'name'       => 'onhand',
                    'title'      => 'Onhand:',
                    'searchable' => false,
                ],
                [
                    'name'       => 'delivered',
                    'title'      => 'Delivered: ',
                    'searchable' => false,
                ]
            ]);
        
        //dd($dataTables->render('sum_amount'));
        //return response()->json($dataTables->scripts());
        // dd($dataTables->scripts());
        // dd($dataTables);   
        return view('report.stockOnLocation')->with([
            'dataTables' => $dataTables,
            'warehouses' => $warehouses,
            'request'    => $request
        ]);            
    }

    public function stockOnStaging($type = 'inbound', Request $request)
    {
        $collections = [];
        $storages = Storage::get();
        $projectId = session()->get('current_project')->id;

        $data['sku'] = $request->input('sku');
        $data['control_date'] = $request->input('control_date');
        $data['ref'] = $request->input('ref');
        $data['storage_id'] = $request->input('storage_id');
        $submit = $request->input('submit');
        // dd($data['storage']);

        $user = User::find(Auth::user()->id);

        if($user->hasRole('WarehouseSupervisor')){
            //get data storages yg hanya ada di warehouse yg sesusai dengan project divre/subdivre supervisor
            // $storages = Storage::join('warehouses as w','w.id','=','storages.warehouse_id')
            //                 ->join('storage_projects as sp','sp.storage_id','=','storages.id')
            //                 ->where('w.branch_id', $user->branch_id)
            //                 ->where('w.id', session()->get('warehouse_id'))
            //                 ->where('sp.project_id', $projectId)
            //                 ->groupBy('storages.id')
            //                 ->get(['storages.code','storages.id']);

            $warehouses = Storage::join('warehouses as w','w.id','=','storages.warehouse_id')
                            ->join('storage_projects as sp','sp.storage_id','=','storages.id')
                            ->where('w.branch_id', $user->branch_id)
                            ->where('w.id', session()->get('warehouse_id'))
                            ->where('sp.project_id', $projectId)
                            ->groupBy('storages.warehouse_id')
                            ->get(['storages.warehouse_id']);

            //return $warehouses;

            foreach($warehouses as $warehouse){
                $warehouse->storages = Storage::where('warehouse_id', $warehouse->warehouse_id)
                            ->groupBy('id')
                            ->get(['code','id']);
            }

            $skus = StockEntryDetail::join('stock_entries as se','se.id','=',
                            'stock_entry_details.stock_entry_id')
                            ->where('se.warehouse_id', session()->get('warehouse_id'))
                            ->where('se.project_id', $projectId)
                            ->where('se.type', 'inbound')
                            ->where('se.status','Completed')
                            ->groupBy('stock_entry_details.item_id')
                            ->get(['stock_entry_details.item_id']);  

            $ref_codes = StockEntryDetail::join('stock_entries as se','se.id','=',
                            'stock_entry_details.stock_entry_id')
                            ->where('se.warehouse_id', session()->get('warehouse_id'))
                            ->where('se.project_id', $projectId)
                            ->where('se.type', 'inbound')
                            ->where('se.status','Completed')
                            ->groupBy('stock_entry_details.ref_code')
                            ->pluck('stock_entry_details.ref_code');

            $control_dates = StockEntryDetail::join('stock_entries as se','se.id','=',
                            'stock_entry_details.stock_entry_id')
                            ->where('se.warehouse_id', session()->get('warehouse_id'))
                            ->where('se.project_id', $projectId)
                            ->where('se.type', 'inbound')
                            ->where('se.status','Completed')
                            ->groupBy('stock_entry_details.control_date')
                            ->pluck('stock_entry_details.control_date');  

            // if(empty($data['sku'])){
            //     $data['sku'] = $skus->first()->item_id;
            // }
            if(empty($data['ref'])){
                $data['ref'] = $ref_codes->first();
            }   

            if($type == 'outbound'){
                $collections = StockEntryDetail::join('stock_entries as se','se.id','=',
                            'stock_entry_details.stock_entry_id')
                            ->where('se.warehouse_id', session()->get('warehouse_id'))
                            ->where('se.project_id', $projectId)
                            ->where('se.status','Completed')
                            ->where('se.type','outbound')

                            // ->where('stock_entry_details.item_id',$data['sku'])
                            ->where('stock_entry_details.ref_code',$data['ref'])

                            ->get(['stock_entry_details.item_id','stock_entry_details.ref_code','stock_entry_details.control_date','stock_entry_details.storage_id','stock_entry_details.qty','se.type','se.code','se.id as se_id']);       
            }
            else{
                $collections = StockTransportDetail::join('stock_transports as st','st.id','=',
                            'stock_transport_details.stock_transport_id')
                            ->where('st.warehouse_id', session()->get('warehouse_id'))
                            ->where('st.project_id', $projectId)
                            ->where('st.status','Completed')
                            ->where('st.type','inbound')

                            // ->where('stock_entry_details.item_id',$data['sku'])
                            ->where('stock_transport_details.ref_code',$data['ref'])

                            ->get(['stock_transport_details.item_id','stock_transport_details.ref_code','stock_transport_details.control_date','stock_transport_details.qty','st.type','st.code','st.id as st_id']);       
            }
              
        }
        
        elseif($user->hasRole('WarehouseManager')){
            $manager_warehouse = Warehouse::where('branch_id', $user->branch_id)->pluck('id');

            // $storages = Storage::join('storage_projects as sp','sp.storage_id','=','storages.id')
            //                 ->whereIn('warehouse_id', $manager_warehouse)
            //                 ->where('sp.project_id', $projectId)
            //                 ->groupBy('storages.id')
            //                 ->get(['storages.code','storages.id']);

            $warehouses = Storage::join('storage_projects as sp','sp.storage_id','=','storages.id')
                            ->whereIn('warehouse_id', $manager_warehouse)
                            ->where('sp.project_id', $projectId)
                            ->groupBy('storages.warehouse_id')
                            ->get(['storages.warehouse_id']);

            //return $warehouses;

            foreach($warehouses as $warehouse){
                $warehouse->storages = Storage::where('warehouse_id', $warehouse->warehouse_id)
                            ->groupBy('id')
                            ->get(['code','id']);
            }

            $skus = StockEntryDetail::join('stock_entries as se','se.id','=',
                            'stock_entry_details.stock_entry_id')
                            ->whereIn('se.warehouse_id', $manager_warehouse)
                            ->where('se.project_id', $projectId)
                            ->where('se.type', 'inbound')
                            ->where('se.status','Completed')
                            ->groupBy('stock_entry_details.item_id')
                            ->get(['stock_entry_details.item_id']);  

            $ref_codes = StockEntryDetail::join('stock_entries as se','se.id','=',
                            'stock_entry_details.stock_entry_id')
                            ->whereIn('se.warehouse_id', $manager_warehouse)
                            ->where('se.project_id', $projectId)
                            ->where('se.type', 'inbound')
                            ->where('se.status','Completed')
                            ->groupBy('stock_entry_details.ref_code')
                            ->pluck('stock_entry_details.ref_code');  

            $control_dates = StockEntryDetail::join('stock_entries as se','se.id','=',
                            'stock_entry_details.stock_entry_id')
                            ->whereIn('se.warehouse_id', $manager_warehouse)
                            ->where('se.project_id', $projectId)
                            ->where('se.type', 'inbound')
                            ->where('se.status','Completed')
                            ->groupBy('stock_entry_details.control_date')
                            ->pluck('stock_entry_details.control_date');   

            // if(empty($data['sku'])){
            //     $data['sku'] = $skus->first()->item_id;
            // }
            if(empty($data['ref'])){
                $data['ref'] = $ref_codes->first();
            }


            //tampilkan semua data stock allocation sesuai warehouse yg dibawahi manager
            if($type == 'outbound'){
                $collections = StockEntryDetail::join('stock_entries as se','se.id','=',
                                'stock_entry_details.stock_entry_id')
                                ->whereIn('se.warehouse_id', $manager_warehouse)
                                ->where('se.project_id', $projectId)
                                ->where('se.status','Completed')
                                ->where('se.type','outbound')

                                // ->where('stock_entry_details.item_id',$data['sku'])
                                ->where('stock_entry_details.ref_code',$data['ref'])

                                ->get(['stock_entry_details.item_id','stock_entry_details.ref_code','stock_entry_details.control_date','stock_entry_details.storage_id','stock_entry_details.qty','se.type','se.code','se.id as se_id']);     
            }
            else{
                $collections = StockTransportDetail::join('stock_transports as st','st.id','=',
                            'stock_transport_details.stock_transport_id')
                            ->where('st.warehouse_id', $manager_warehouse)
                            ->where('st.project_id', $projectId)
                            ->where('st.status','Completed')
                            ->where('st.type','inbound')

                            // ->where('stock_entry_details.item_id',$data['sku'])
                            ->where('stock_transport_details.ref_code',$data['ref'])

                            ->get(['stock_transport_details.item_id','stock_transport_details.ref_code','stock_transport_details.control_date','stock_transport_details.qty','st.type','st.code','st.id as st_id']);       
            }
        }


        elseif($user->hasRole('CargoOwner') || $user->hasRole('Admin-BGR')){
            // $storages = Storage::join('storage_projects as sp','sp.storage_id','=','storages.id')
            //                 ->where('sp.project_id', $projectId)
            //                 ->groupBy('storages.id')
            //                 ->get(['storages.code','storages.id']);

            $warehouses = Storage::join('storage_projects as sp','sp.storage_id','=','storages.id')
                            ->where('sp.project_id', $projectId)
                            ->groupBy('storages.warehouse_id')
                            ->get(['storages.warehouse_id']);

            //return $warehouses;

            foreach($warehouses as $warehouse){
                $warehouse->storages = Storage::where('warehouse_id', $warehouse->warehouse_id)
                            ->groupBy('id')
                            ->get(['code','id']);
            }

            $skus = StockEntryDetail::join('stock_entries as se','se.id','=',
                            'stock_entry_details.stock_entry_id')
                            ->where('se.project_id', $projectId)
                            ->where('se.type', 'inbound')
                            ->where('se.status','Completed')
                            ->groupBy('stock_entry_details.item_id')
                            ->get(['stock_entry_details.item_id']);  

            $ref_codes = StockEntryDetail::join('stock_entries as se','se.id','=',
                            'stock_entry_details.stock_entry_id')
                            ->where('se.project_id', $projectId)
                            ->where('se.type', 'inbound')
                            ->where('se.status','Completed')
                            ->groupBy('stock_entry_details.ref_code')
                            ->pluck('stock_entry_details.ref_code');   

            $control_dates = StockEntryDetail::join('stock_entries as se','se.id','=',
                            'stock_entry_details.stock_entry_id')
                            ->where('se.project_id', $projectId)
                            ->where('se.type', 'inbound')
                            ->where('se.status','Completed')
                            ->groupBy('stock_entry_details.control_date')
                            ->pluck('stock_entry_details.control_date');   

            // if(empty($data['sku'])){
            //     $data['sku'] = $skus->first()->item_id;
            // }
            if(empty($data['ref'])){
                $data['ref'] = $ref_codes->first();
            }

            //tampilkan semua data stock allocation sesuai project yg aktif
            if($type == 'outbound'){
                $collections = StockEntryDetail::join('stock_entries as se','se.id','=',
                                'stock_entry_details.stock_entry_id')
                                ->where('se.project_id', $projectId)
                                ->where('se.status','Completed')
                                ->where('se.type','outbound')

                                // ->where('stock_entry_details.item_id',$data['sku'])
                                ->where('stock_entry_details.ref_code',$data['ref'])

                                ->get(['stock_entry_details.item_id','stock_entry_details.ref_code','stock_entry_details.control_date','stock_entry_details.storage_id','stock_entry_details.qty','se.type','se.code','se.id as se_id']); 
            }
            else{
                $collections = StockTransportDetail::join('stock_transports as st','st.id','=',
                            'stock_transport_details.stock_transport_id')
                            ->where('st.project_id', $projectId)
                            ->where('st.status','Completed')
                            ->where('st.type','inbound')

                            // ->where('stock_entry_details.item_id',$data['sku'])
                            ->where('stock_transport_details.ref_code',$data['ref'])

                            ->get(['stock_transport_details.item_id','stock_transport_details.ref_code','stock_transport_details.control_date','stock_transport_details.qty','st.type','st.code','st.id as st_id']);       
            }

            

            //return $collections->count();

            //$result = $data['sku'].' '.$data['ref'].' '.$data['control_date'].' '.$data['storage_id'];

            //return $result;    

            //return $collections;
        }

        //return $collections;

        // $collections = $collections->filter(function ($item) {
        //     return $item->type_stock != "saout";
        // });

        //return $collections;

        // dd($collections);
        // Filter data collection
        empty($data['ref']) ?: $collections = $collections->where('ref_code', $data['ref']);
        empty($data['sku']) ?: $collections = $collections->where('item_id', $data['sku']);
        empty($data['control_date']) ?: $collections = $collections->where('control_date', $data['control_date']);
        empty($data['storage_id']) ?: $collections = $collections->where('storage_id', $data['storage_id']);

        $stock = 0;
        
        if($type == 'outbound'){
            foreach($collections as $collection => $value){
            
                $stockDelivery = StockDelivery::where('stock_entry_id', $value->se_id)->get()->first();
                if($stockDelivery){
                    if($stockDelivery->status == 'Completed'){
                        $collections->forget($collection);continue;
                    }
                }
            }

        }
        else{
            foreach($collections as $collection => $value){
            
                $stockEntry = StockEntry::where('stock_transport_id', $value->st_id)->get()->first();
                if($stockEntry){
                    if($stockEntry->status == 'Completed'){
                        $collections->forget($collection);continue;
                    }
                }
            }

        }

        //hitung stock fix
        foreach($collections as $collection){
            $stock += $collection->qty;
        }
        
        //return $collections;

        if($submit == 1 && $type == 'inbound'){
            return Excel::download(new ReportExport($collections,6), 'StockOnStagingINList.xlsx');
        }
        elseif($submit == 1 && $type == 'outbound'){
            return Excel::download(new ReportExport($collections,7), 'StockOnStagingOUTList.xlsx');
        }
        elseif($submit == 0){
            //return $collections;

            return view('report.stockOnStaging', compact('storages', 'data', 'collections','skus','ref_codes','control_dates','warehouses','stock','type'));
        }

                    
    }

    public function managementStock(Request $request)
    {
        // dd($request);
        if(!(Auth::user()->hasRole('WarehouseManager') || Auth::user()->hasRole('Admin-BGR') || Auth::user()->hasRole('SPI') || Auth::user()->hasRole('Reporting') || Auth::user()->hasRole('WarehouseSupervisor'))) {
            abort(403);
        }
        
        // dd($request->input('date_from'));
        
        $warehouse = ContractWarehouse::join('contracts', 'contracts.id', 'contract_warehouse.contract_id')
            ->join('warehouses', 'warehouses.id', 'contract_warehouse.warehouse_id')
            ->join('projects', 'projects.id', 'contracts.project_id')
            ->join('parties', 'parties.id', 'warehouses.branch_id')
            ->where('contracts.project_id', session()->get('current_project')->id)
            ->where('contracts.is_active', 1);
            // if((!Auth::user()->hasRole('Admin-BGR') || !Auth::user()->hasRole('SPI') || !Auth::user()->hasRole('Reporting')) && Auth::user()->branch_id != null) {
            //     $warehouse->where('warehouses.branch_id', Auth::user()->branch->id);
            // } 
            if($request->warehouse_id) {
                $warehouse->where('warehouses.id', $request->input('warehouse_id'));
            }
            $warehouse = $warehouse->select(['warehouses.name', 'warehouses.code', 'warehouses.id', 'projects.name as project_name', 'parties.name as branch_name'])->first();
            
        // dd($warehouse);

        $data['warehouse_id'] = $request->input('warehouse_id');
        $data['date_from'] = $request->date_from;
        $data['date_to'] = $request->input('date_to');

        $submit = $request->input('submit');

        $search = !empty($data['warehouse_id']) && !empty($data['date_to']) && !empty($data['date_from']);
        // dd($search);
        $additionalMessage = '';
        $additionalError = true;
     
        if($request->submit == 1){
            // return Excel::download(new ReportExport(app('App\Http\Controllers\ReportApiController')->contractManagement($request),11), 'Report Contract.xlsx');
            try {
                // return Excel::download(new ReportExport(app('App\Http\Controllers\ReportApiController')->managementStock($request)['data'],13), 'Report Stock '.$data['date_to'] .'.xlsx');
                return Excel::download(new ManagementStockExport($data['date_from'], $data['date_to'], $data['warehouse_id']), 'Report Management Stock-'.Carbon::now()->format('d-m-y').'.xlsx');

            }
            catch (\Exception $e) {
                dd($e->getMessage());
            }
        }elseif($request->submit == 2)
        {
            if($data['warehouse_id'] == 'all') {
                // dd('masuk');
                $additionalError = true;
                $additionalMessage = 'Harap Pilih Salah Satu Warehouse';
                // dd('sni?');
                return view('report.managementStock')->with([
                    'data' => $data,
                    'search' => $search,
                    'additionalMessage' => $additionalMessage,
                    'additionalError' => $additionalError
                ]);
            } else {
                $warehouse = Warehouse::find($data['warehouse_id']);
                // dd($data['date_to']);
                // dd($request->date_from);
                $data_all = app('App\Http\Controllers\ReportApiController')->managementStock($request)['data'];
                // dd($warehouse->warehouse_officer->where());
                return view('report.print_stockopname')->with([
                    'data' => $data_all,
                    'warehouse' => $warehouse,
                    'filter' => $request->date_to
                ]);
            }
        } elseif($request->submit == 3){
            if($data['warehouse_id'] == 'all') {
                // dd('masuk');
                $additionalError = true;
                $additionalMessage = 'Harap Pilih Salah Satu Warehouse';
                // dd('sni?');
                return view('report.managementStock')->with([
                    'data' => $data,
                    'search' => $search,
                    'additionalMessage' => $additionalMessage,
                    'additionalError' => $additionalError
                ]);
            } else {
                $warehouse = Warehouse::find($data['warehouse_id']);
                // dd($data['date_to']);
                // dd($request->date_from);
                $data_all = app('App\Http\Controllers\ReportApiController')->managementStock($request)['data'];
                // dd($warehouse->warehouse_officer->where());
                return view('report.print_stockopname_wina')->with([
                    'data' => $data_all,
                    'warehouse' => $warehouse,
                    'filter' => $request->date_from,
                    'filter_2' => $request->date_to
                ]);
            }
        }else{ 
            // dd($data);
            return view('report.managementStock')->with([
                'data' => $data,
                'search' => $search,
                'additionalMessage' => $additionalMessage,
                'additionalError' => $additionalError,
                'warehouse' => $warehouse
            ]);
        }
        
    }

    public function stockMutationLocation(Request $request)
    {
        // dd($request);
        // dd($request->input('date_from'));
        if(!(Auth::user()->hasRole('WarehouseManager') || Auth::user()->hasRole('WarehouseSupervisor')|| Auth::user()->hasRole('Admin-BGR') || Auth::user()->hasRole('SPI') || Auth::user()->hasRole('Reporting') || Auth::user()->hasRole('CommandCenter'))) {
            abort(403);
        }

        $data['warehouse_id'] = $request->input('warehouse_id');
        $data['item'] = $request->input('item');
        $data['storage'] = $request->input('storage');
        // dd($data['storage']);
        // dd($data['item']);

        $submit = $request->input('submit');

        $warehouse = ContractWarehouse::join('contracts', 'contracts.id', 'contract_warehouse.contract_id')
            ->join('warehouses', 'warehouses.id', 'contract_warehouse.warehouse_id')
            ->join('projects', 'projects.id', 'contracts.project_id')
            ->join('parties', 'parties.id', 'warehouses.branch_id')
            ->where('contracts.project_id', session()->get('current_project')->id)
            ->where('contracts.is_active', 1)
            ->where('warehouses.id', $request->input('warehouse_id'))
            ->where('warehouses.branch_id', Auth::user()->branch->id)
            ->select(['warehouses.name', 'warehouses.code', 'warehouses.id', 'projects.name as project_name', 'parties.name as branch_name'])->first();

        $search = !empty($data['warehouse_id']);
        // dd($search);
        $additionalMessage = '';
        $additionalError = true;
     
        if($request->submit == 1){
            try {
                if($data['warehouse_id'] == 'all') {
                    return redirect()->back()->withErrors('Silahkan pilih salah satu gudang');
                }
                return Excel::download(new StockOnLocationReport($data['item'],$data['warehouse_id'],$data['storage']), 'Laporan Stock Per Lokasi-'.Carbon::now()->format('d-m-y').'.xlsx');
                
            }
            catch (\Exception $e) {
                dd($e->getMessage());
            }
        }
        if($request->submit == 2){
            // return Excel::download(new ReportExport(app('App\Http\Controllers\ReportApiController')->contractManagement($request),11), 'Report Contract.xlsx');
            try {
                $warehouse = Warehouse::find($data['warehouse_id']);
                // dd($data['date_to']);
                // dd($request->date_from);
                $data_all = app('App\Http\Controllers\ReportApiController')->mutationOnLoction($request)['data'];
                // dd($warehouse->warehouse_officer->where());
                return view('report.print_stockonlocationreport')->with([
                    'data' => $data_all,
                    'warehouse' => $warehouse
                ]);
            }
            catch (\Exception $e) {
                dd($e->getMessage());
            }
        }else{ 
            // dd($data);
            return view('report.stockLocation')->with([
                'data' => $data,
                'search' => $search,
                'additionalMessage' => $additionalMessage,
                'additionalError' => $additionalError,
                'warehouse' => $warehouse
            ]);
        }
        
    }

    public function stockMutation(Request $request) {
        $projectId = session()->get('current_project')->id;
        $data['item'] = $request->input('item');
        $data['date_from'] = $request->input('date_from');
        $data['date_to'] = $request->input('date_to');
        $data['warehouse'] = $request->input('warehouse');

        $jumlah_outhandling = null;
        $jumlah_inhandling = null;

        $submit = $request->input('submit');

        $search = !empty($data['item']) || !empty($data['date_from']) && !empty($data['date_to'] && !empty($data['warehouse']));

        $additionalMessage = '';
        $additionalError = true;

        if (!empty($data['item'])) {
            if(empty($data['date_from']) && empty($data['date_to'])){
                $additionalMessage = 'Harap pilih tanggal mulai dan tanggal akhir';
            }else if  (empty($data['date_from'])){
                $additionalMessage = 'Harap pilih tanggal mulai';
            }else if (empty($data['date_to'])){
                $additionalMessage = 'Harap pilih tanggal akhir';
            }else if (empty($data['warehouse'])){
                $additionalMessage = 'Harap pilih Warehouse';
            }
        }else{
            if(!empty($data['date_from']) || !empty($data['date_to'])){
                $additionalMessage = 'Harap pilih item';
            }
        }

        $item = Item::find($data['item']);
        // dd($item);
        // dd($data['item']);
        $collections = [];
        if (!empty($item)) {

            $show = 1;
            $begining_receiving = StockEntryDetail::join('stock_entries as se', 'se.id', '=', 'stock_entry_details.stock_entry_id')
                            ->join('stock_transports', 'stock_transports.id', 'se.stock_transport_id')
                            ->join('stock_advance_notices', 'stock_advance_notices.id', 'stock_transports.advance_notice_id')
                            ->where('advance_notice_activity_id','<>', 20)
                            ->where('se.type','inbound')
                            ->where(DB::raw('Date(se.updated_at)'),'<' ,$data['date_from'])
                            ->where('stock_entry_details.item_id', $item->id)
                            ->where('se.project_id', $projectId)
                            ->where('se.status','Completed');
                            if($data['warehouse']!= '0') {
                                $begining_receiving->where('se.warehouse_id', $data['warehouse']);
                            }
                            $begining_receiving = $begining_receiving->select([
                                'se.id as stock_transport_id',
                                'stock_entry_details.item_id',
                                DB::raw('sum(stock_entry_details.qty) as qty'),
                            ])
                            ->sum('qty');
            // dd($begining_receiving)
            $begining_delivery = StockEntryDetail::join('stock_entries as se', 'se.id', '=', 'stock_entry_details.stock_entry_id')
                            ->join('stock_transports', 'stock_transports.id', 'se.stock_transport_id')
                            ->join('stock_advance_notices', 'stock_advance_notices.id', 'stock_transports.advance_notice_id')
                            ->where('advance_notice_activity_id','<>', 20)
                            ->where('se.type','outbound')
                            ->where(DB::raw('Date(se.updated_at)'), '<' ,$data['date_from'])
                            ->where('stock_entry_details.item_id', $item->id)
                            ->where('se.project_id', $projectId)
                            ->where('se.status','Completed');
                            
                        if($data['warehouse']!= '0') {
                            $begining_delivery
                            ->where('se.warehouse_id', $data['warehouse']);
                        }
                        $begining_delivery = $begining_delivery->select([
                                'se.id as stock_delivery_id',
                                'stock_entry_details.item_id',
                                DB::raw('sum(stock_entry_details.qty) as qty'),
                            ])
                            ->sum('qty');


            $master_standing = AdvanceNotice::join('stock_advance_notice_details', 'stock_advance_notices.id', 'stock_advance_notice_details.stock_advance_notice_id')
            // ->where(DB::raw('Date(stock_advance_notices.created_at)'), '<=' ,$data['date_to'])
            ->where('stock_advance_notice_details.item_id', $item->id)
            ->where('advance_notice_activity_id','<>', 20)
            ->where('stock_advance_notices.project_id', $projectId)
            ->where('stock_advance_notices.status' , '<>', 'Closed');

            if($data['warehouse']!= '0') {
                $master_standing
                ->where('stock_advance_notices.warehouse_id', $data['warehouse']);
            }
            $master_standing = $master_standing->select(DB::raw('sum(stock_advance_notice_details.qty) as qty_details,stock_advance_notice_id'))->groupBy('stock_advance_notice_id');
            // dd($master_standing->where('stock_advance_notices.type', 'inbound')toSql);
             // $inbound = (clone $staging)->where('st.type', 'inbound')->first();
            $inbound_details = (clone $master_standing)->where('stock_advance_notices.type', 'inbound')->pluck('qty_details', 'stock_advance_notice_id');
            $outbound_details = (clone $master_standing)->where('stock_advance_notices.type', 'outbound')->pluck('qty_details', 'stock_advance_notice_id');
            // dd($master_standing->toSql());
            // dd($outbound_details);
            // dd($inbound_details);
            $master_transport = StockTransport::join('stock_transport_details', 'stock_transport_details.stock_transport_id', 'stock_transports.id')
            ->join('stock_advance_notices', 'stock_advance_notices.id', 'stock_transports.advance_notice_id')
            ->where('advance_notice_activity_id','<>', 20)
            ->where('stock_transport_details.item_id', $item->id)
            ->where('stock_transports.project_id', $projectId)
            ->where('stock_transports.status' , '<>', 'Closed');

            if($data['warehouse']!= '0') {
                $master_transport = $master_transport
                ->where('stock_transports.warehouse_id', $data['warehouse']);
            }

            // foreach ($)
            $arr = collect();
            $outarr = collect();
            $total_inbound = 0;
            foreach ($inbound_details as $key => $inbound_detail) {
                if($key != ''){
                    $arr->push($key);
                } else {
                    $arr->push(0);
                }
                if($inbound_detail != null) {
                    $total_inbound += $inbound_detail;
                } else {
                    $total_inbound += 0;
                }
            }

            $total_outbound = 0;
            // dd($outbound_details);
            foreach ($outbound_details  as $key => $outbound_detail) {
                if($key != ''){
                    $outarr->push($key);
                } else {
                    $outarr->push(0);
                }
                if($outbound_detail != null) {
                    $total_outbound += $outbound_detail;
                } else {
                    $total_outbound += 0;
                }
            }

            $inbound_transport = (clone $master_transport)->where('stock_transports.type', 'inbound')->whereIn('advance_notice_id', $arr)->sum('qty');
            $outbound_transport = (clone $master_transport)->where('stock_transports.type', 'outbound')->whereIn('advance_notice_id', $outarr)->sum('plan_qty');
            // dd($outbound_transport);
            // dd($total_outbound,$outbound_transport);
          
            $jumlah_inhandling = $total_inbound - $inbound_transport;
            $jumlah_outhandling = $total_outbound - $outbound_transport;
            // dd($outbound_transport);
            // $begining = 0;
            // dd($begining_delivery);
            $beginings = $begining_receiving - $begining_delivery;
            // dd($beginings);
            $periods = CarbonPeriod::create($data['date_from'], $data['date_to']);
            // dd(count($periods) - 19);
            $i = 0;
            $countPeriods = count($periods);
            // dd($collections);
            foreach ($periods as $period) {
                $dateItem = $period->format('Y-m-d');
                $receiving_item = StockEntryDetail::join('stock_entries as se', 'se.id', '=', 'stock_entry_details.stock_entry_id')
                            ->join('stock_transports', 'stock_transports.id', 'se.stock_transport_id')
                            ->join('stock_advance_notices', 'stock_advance_notices.id', 'stock_transports.advance_notice_id')
                            ->where('advance_notice_activity_id','<>', 20)
                            ->where('se.type','inbound')
                            ->where(DB::raw('Date(se.updated_at)'), $dateItem)
                            ->where('stock_entry_details.item_id', $item->id)
                            ->where('se.project_id', $projectId)
                            ->where('se.status','Completed');
                            if($data['warehouse']!= '0') {
                                $receiving_item->where('se.warehouse_id', $data['warehouse']);
                            }
                            $receiving_item = $receiving_item->select([
                                'se.id as stock_transport_id',
                                'stock_entry_details.item_id',
                                DB::raw('sum(stock_entry_details.qty) as qty_receiving'),
                                DB::raw('DATE(se.updated_at) as transaction_date'),
                            ])
                            ->groupBy('transaction_date')
                            ->orderBy('transaction_date', 'ASC')
                            ->first();

                $qty_issuing = StockEntryDetail::join('stock_entries as se', 'se.id', '=', 'stock_entry_details.stock_entry_id')
                                ->join('stock_transports', 'stock_transports.id', 'se.stock_transport_id')
                                ->join('stock_advance_notices', 'stock_advance_notices.id', 'stock_transports.advance_notice_id')
                                ->where('advance_notice_activity_id','<>', 20)
                                ->where('se.type','outbound')
                                ->where(DB::raw('Date(se.updated_at)'), $dateItem)
                                ->where('stock_entry_details.item_id', $item->id)
                                ->where('se.project_id', $projectId)
                                ->where('se.status','Completed');
                                if($data['warehouse']!= '0') {
                                    $qty_issuing->where('se.warehouse_id', $data['warehouse']);
                                }
                                $qty_issuing = $qty_issuing->select([
                                    'se.id as stock_delivery_id',
                                    'stock_entry_details.item_id',
                                    DB::raw('sum(stock_entry_details.qty) as qty_issuing'),
                                    DB::raw('DATE(se.updated_at) as transaction_date')
                                ])
                                ->groupBy('transaction_date')
                                ->orderBy('transaction_date', 'ASC')
                                ->first();
                // staging
                $a = 0;
                if (!empty($receiving_item) || !empty($qty_issuing)) {
                    // $a+=1;
                    $receiving = !empty($receiving_item) ? $receiving_item->qty_receiving : 0;
                    $delivery = !empty($qty_issuing) ? $qty_issuing->qty_issuing : 0;
                    // $instanding_inbound = !empty($inbound) ? $inbound->qty_details_staging - $inbound->qty_transport_staging : 0;
                    // $outstanding_outbound = !empty($outbound) ? $outbound->qty_details_staging - $outbound->qty_transport_staging: 0;
                    $closing = ($beginings + $receiving) - $delivery;
                    $result = [
                        'date' => $dateItem,
                        'item' => $item->sku.' '.$item->name,
                        'uom_name' => $item->default_uom->name,
                        'begining' => $beginings,
                        'receiving' => $receiving,
                        'instanding' => $i == 0 ? $jumlah_inhandling : '0',
                        'delivery' => $delivery,
                        'outstanding' => $i == 0 ? $jumlah_outhandling : '0',
                        'closing' => $closing,
                    ];
                    
                    $beginings = $closing;
                    array_push($collections, $result);
                    // array_reverse($collections,true);
                    // dd($collections);
                    $i++;
                    // $a+1;
                }
                // echo $i;
                // array_push($collections, $jumlah_inhandling, $jumlah_outhandling);
            }
            // echo count($a);   
            //return $collections;

        }
        elseif ($data['item'] == 'all' && empty($item)){
            $show = 0;
            $item_finds = ItemProjects::where('project_id', $projectId)->get();
            // dd($item_finds);
            //return $item_finds;

            //$items = Item::leftJoin('item_projects', 'items.id', 'item_projects.item_id')->where('item_projects.project_id', $projectId)->select(['items.*'])->get();
            // $item = Item::find($data['item']);
           
            // $begining = 0;
            // dd($begining_delivery);
            $periods = CarbonPeriod::create($data['date_from'], $data['date_to']);
            $i = 0;
            foreach ($item_finds as $key => $item_find) {
                $begining_receiving = StockEntryDetail::join('stock_entries as se', 'se.id', '=', 'stock_entry_details.stock_entry_id')
                            ->join('stock_transports', 'stock_transports.id', 'se.stock_transport_id')
                            ->join('stock_advance_notices', 'stock_advance_notices.id', 'stock_transports.advance_notice_id')
                            ->where('advance_notice_activity_id','<>', 20)
                            ->where('se.type','inbound')
                            ->where(DB::raw('Date(se.updated_at)'),'<' ,$data['date_from'])
                            ->where('stock_entry_details.item_id', $item_find->item_id)
                            ->where('se.project_id', $projectId)
                            ->where('se.status','Completed');
                            if($data['warehouse']!= '0') {
                                $begining_receiving->where('se.warehouse_id', $data['warehouse']);
                            }
                            $begining_receiving = $begining_receiving->select([
                                'se.id as stock_transport_id',
                                'stock_entry_details.item_id',
                                DB::raw('sum(stock_entry_details.qty) as qty'),
                            ])
                            ->sum('qty');
            // dd($begining_receiving)
            $begining_delivery = StockEntryDetail::join('stock_entries as se', 'se.id', '=', 'stock_entry_details.stock_entry_id')
                        ->join('stock_transports', 'stock_transports.id', 'se.stock_transport_id')
                        ->join('stock_advance_notices', 'stock_advance_notices.id', 'stock_transports.advance_notice_id')
                        ->where('advance_notice_activity_id','<>', 20)
                        ->where('se.type','outbound')
                        ->where(DB::raw('Date(se.updated_at)'), '<' ,$data['date_from'])
                        ->where('stock_entry_details.item_id', $item_find->item_id)
                        ->where('se.project_id', $projectId)
                        ->where('se.status','Completed');
                        
                    if($data['warehouse']!= '0') {
                        $begining_delivery
                        ->where('se.warehouse_id', $data['warehouse']);
                    }
                    $begining_delivery = $begining_delivery->select([
                            'se.id as stock_delivery_id',
                            'stock_entry_details.item_id',
                            DB::raw('sum(stock_entry_details.qty) as qty'),
                        ])
                        ->sum('qty');
            $beginings = $begining_receiving - $begining_delivery;

                $master_standing = AdvanceNotice::join('stock_advance_notice_details', 'stock_advance_notices.id', 'stock_advance_notice_details.stock_advance_notice_id')
                // ->where(DB::raw('Date(stock_advance_notices.created_at)'), '<=' ,$data['date_to'])
                ->where('stock_advance_notice_details.item_id', $item_find->item_id)
                ->where('advance_notice_activity_id','<>', 20)
                ->where('stock_advance_notices.project_id', $projectId)
                ->where('stock_advance_notices.status' , '<>', 'Closed');

                if($data['warehouse']!= '0') {
                    $master_standing
                    ->where('stock_advance_notices.warehouse_id', $data['warehouse']);
                }
                $master_standing = $master_standing->select(DB::raw('sum(stock_advance_notice_details.qty) as qty_details,stock_advance_notice_id'))->groupBy('stock_advance_notice_id');
                // dd($master_standing->where('stock_advance_notices.type', 'inbound')toSql);
                // $inbound = (clone $staging)->where('st.type', 'inbound')->first();
                $inbound_details = (clone $master_standing)->where('stock_advance_notices.type', 'inbound')->pluck('qty_details', 'stock_advance_notice_id');
                $outbound_details = (clone $master_standing)->where('stock_advance_notices.type', 'outbound')->pluck('qty_details', 'stock_advance_notice_id');
                $master_transport = StockTransport::join('stock_transport_details', 'stock_transport_details.stock_transport_id', 'stock_transports.id')
                ->join('stock_advance_notices', 'stock_advance_notices.id', 'stock_transports.advance_notice_id')
                ->where('advance_notice_activity_id','<>', 20)
                ->where('stock_transport_details.item_id', $item_find->item_id)
                ->where('stock_transports.project_id', $projectId)
                ->where('stock_transports.status' , '<>', 'Closed');

                if($data['warehouse']!= '0') {
                    $master_transport = $master_transport
                    ->where('stock_transports.warehouse_id', $data['warehouse']);
                }

                // foreach ($)
                $arr = collect();
                $outarr = collect();
                $total_inbound = 0;
                foreach ($inbound_details as $key => $inbound_detail) {
                    if($key != ''){
                        $arr->push($key);
                    } else {
                        $arr->push(0);
                    }
                    if($inbound_detail != null) {
                        $total_inbound += $inbound_detail;
                    } else {
                        $total_inbound += 0;
                    }
                }

                $total_outbound = 0;
                // dd($outbound_details);
                foreach ($outbound_details  as $key => $outbound_detail) {
                    if($key != ''){
                        $outarr->push($key);
                    } else {
                        $outarr->push(0);
                    }
                    if($outbound_detail != null) {
                        $total_outbound += $outbound_detail;
                    } else {
                        $total_outbound += 0;
                    }
                }

                $inbound_transport = (clone $master_transport)->where('stock_transports.type', 'inbound')->whereIn('advance_notice_id', $arr)->sum('qty');
                $outbound_transport = (clone $master_transport)->where('stock_transports.type', 'outbound')->whereIn('advance_notice_id', $outarr)->sum('plan_qty');
                // dd($outbound_transport);
                // dd($total_outbound,$outbound_transport);
                
                $jumlah_inhandling = $total_inbound - $inbound_transport;
                $jumlah_outhandling = $total_outbound - $outbound_transport;
                foreach ($periods as $period) {
                    // dd($item_find);
                    $dateItem = $period->format('Y-m-d');
                    $receiving_item = StockTransportDetail::join('stock_transports as st', 'st.id', '=', 'stock_transport_details.stock_transport_id')
                                ->where('st.type','inbound')
                                ->where(DB::raw('Date(st.updated_at)'), $dateItem)
                                ->where('stock_transport_details.item_id', $item_find->item->id)
                                ->where('st.project_id', $projectId)
                                ->where('st.status','Completed');
                                if($data['warehouse']!= '0') {
                                    $receiving_item
                                        ->where('st.warehouse_id', $data['warehouse']);
                                }
                                $receiving_item = $receiving_item->select([
                                    'st.id as stock_transport_id',
                                    'stock_transport_details.item_id',
                                    DB::raw('sum(stock_transport_details.qty) as qty_receiving'),
                                    DB::raw('DATE(st.updated_at) as transaction_date'),
                                ])
                                ->groupBy('transaction_date', 'stock_transport_details.item_id')
                                ->orderBy('transaction_date', 'ASC')
                                ->first();

                    $qty_issuing = StockDeliveryDetail::join('stock_deliveries as sd', 'sd.id', '=', 'stock_delivery_details.stock_delivery_id')
                                    ->where('sd.type','outbound')
                                    ->where(DB::raw('Date(sd.updated_at)'), $dateItem)
                                    ->where('stock_delivery_details.item_id', $item_find->item->id)
                                    ->where('sd.project_id', $projectId)
                                    ->where('sd.status','Completed');

                                    if($data['warehouse']!= '0') {
                                        $qty_issuing->join('stock_entries', 'stock_entries.id', 'sd.stock_entry_id')
                                            ->where('stock_entries.warehouse_id', $data['warehouse']);
                                    }
                                    $qty_issuing = $qty_issuing->select([
                                        'sd.id as stock_delivery_id',
                                        'stock_delivery_details.item_id',
                                        DB::raw('sum(stock_delivery_details.qty) as qty_issuing'),
                                        DB::raw('DATE(sd.updated_at) as transaction_date')
                                    ])
                                    ->groupBy('transaction_date', 'stock_delivery_details.item_id')
                                    ->orderBy('transaction_date', 'ASC')
                                    ->first();
                    if (!empty($receiving_item) || !empty($qty_issuing)) {
                        $receiving = !empty($receiving_item) ? $receiving_item->qty_receiving : 0;
                        $delivery = !empty($qty_issuing) ? $qty_issuing->qty_issuing : 0;
                        $closing = ($beginings + $receiving) - $delivery;
                        // $item = !empty($item) ? $item->id : 0;
                        // var_dump($item_find);
                        $result = [
                            'date' => $dateItem,
                            'item' => $item_find->item->name,
                            'uom_name' => $item_find->item->default_uom->name,
                            'begining' => $beginings,
                            'receiving' => $receiving,
                            'instanding' => $i == 0 ? $jumlah_inhandling : '0',
                            'delivery' => $delivery,
                            'closing' => $closing,
                            'outstanding' => $i == 0 ? $jumlah_outhandling : '0',
                            // 'item' => !empty($receiving_item->item_id) ? Item::find($receiving_item->item_id)->name : '',
                        ];
                        $beginings = $closing;
                        array_push($collections, $result);
                    }

                }
            }
        }else{
            $show = 0;
        }

        if($submit == 1){
            // return Excel::download(new ReportExport($collections,2), 'StockMutationList_'.$data['date_from'].'_'.$data['date_to'].'.xlsx');
            return Excel::download(new MutasiStockExport($data['date_from'], $data['date_to'],$data['item'] ,$data['warehouse']), 'Report Mutasi Stock-'.Carbon::now()->format('d-m-y').'.xlsx');

        }
        else{
            // die;
            // foreach($collections as $key =>)
            $myArr = [];
            if($data['item'] == 'all' && empty($item)) {
                foreach($collections as $key => $collection){
                    $myArr[$collection['item']][] = 
                    [
                        'date'=>$collection['date'],
                        'uom_name'=>$collection['uom_name'],
                        'begining'=>$collection['begining'],
                        'receiving'=>$collection['receiving'],
                        'instanding'=>$collection['instanding'],
                        'delivery'=>$collection['delivery'],
                        'closing'=>$collection['closing'],
                        'outstanding'=>$collection['outstanding'],
                    ];

                //     dd($collection['FS Terigu']);
                //     foreach($collection as $c) {
                //         dd($c);
                //     }
                }
                $collections = $myArr;
            }
            return view('report.stockMutation', compact('show', 'data', 'search', 'item', 'collections', 'additionalError', 'additionalMessage', 'jumlah_outhandling', 'jumlah_inhandling'));
        }

        
    }

    public function stockDeliverMutation(Request $request)
    {
        $projectId = session()->get('current_project')->id;
        $data['date_from'] = $request->input('date_from');
        $data['date_to'] = $request->input('date_to');
        $data['project'] = $request->input('project');

        $submit = $request->input('submit');

        $search = !empty($data['date_from']) && !empty($data['date_to']);

        $additionalMessage = '';
        $additionalError = true;
        

        // $item = Item::find($data['item']);
        // dd($item);
        // dd($data['item']);
        $collections = [];
        if ($search) {
            $show = 1;
            // sisa pengiriman awal
            // pengiriman dibuat
            // pengiriman sampai
            // sisa pengiriman akhir
            // dd($begining_receiving)


            // sisa pengiriman awal didapatkan melalui nomor gi yang belum received tp sudah complete di gudang lebih kecil dari hari yang di filter
            // pengiiriman dibuat adalah ketika udah complete di gudang
            // pengiriman sampai adalah ketika sudah received
            // pengiriman akhir ketika dia di tanggal itu tp 
            $begining_delivery = StockDelivery::where(DB::raw('Date(stock_deliveries.created_at)'), '<' ,$data['date_from'])
            ->join('stock_entries', 'stock_entries.id', 'stock_deliveries.stock_entry_id')
            ->whereIn('warehouse_id', ['892', '1385', '888'])
            ->where('stock_entries.project_id','<>', '333');
                    if($data['project'] != 'all') 
                    {
                        $begining_delivery
                        ->where('stock_deliveries.project_id', $data['project']); 
                    }
                    $begining_delivery->whereIn('stock_deliveries.status',['Received', 'Completed']);
                // ->where('stock_deliveries.date_received', '<>', null);    
            $begining_delivery = $begining_delivery->select([
                        'stock_deliveries.id as stock_delivery_id',
                    ])
                    ->count('stock_deliveries.id');
            $receiving_item = StockDelivery::where(DB::raw('Date(stock_deliveries.date_received)'), '<' ,$data['date_from'])
            ->join('stock_entries', 'stock_entries.id', 'stock_deliveries.stock_entry_id')
            ->whereIn('warehouse_id', ['892','1385', '888'])
            ->where('stock_entries.project_id','<>', '333');
                    if($data['project'] != 'all') 
                    {
                        $receiving_item
                        ->where('stock_deliveries.project_id', $data['project']); 
                    }
                    $receiving_item->whereIn('stock_deliveries.status',['Received']);
                // ->where('stock_deliveries.date_received', '<>', null);    
            $receiving_item = $receiving_item->select([
                        'stock_deliveries.id as stock_delivery_id',
                    ])
                    ->count('stock_deliveries.id');

        
            $beginings = $begining_delivery - $receiving_item;
            // dd($beginings);
            $periods = CarbonPeriod::create($data['date_from'], $data['date_to']);
            // dd(count($periods) - 19);
            $i = 0;
            $countPeriods = count($periods);
            // dd($collections);
            foreach ($periods as $period) {
                $dateItem = $period->format('Y-m-d');
                $receiving_item = StockDelivery::where(DB::raw('Date(stock_deliveries.created_at)'), $dateItem)
                            ->join('stock_entries', 'stock_entries.id', 'stock_deliveries.stock_entry_id')
            ->whereIn('warehouse_id', ['892', '1385', '888'])
            ->where('stock_entries.project_id','<>', '333');

                            // ->where('stock_deliveries.project_id', $projectId)
                            if($data['project'] != 'all') 
                            {
                                $receiving_item
                                ->where('stock_deliveries.project_id', $data['project']); 
                            }
                            $receiving_item->whereIn('stock_deliveries.status',['Completed', 'Received']);
                            $receiving_item = $receiving_item->select([
                                DB::raw('count(stock_deliveries.id) as qty_receiving'),
                                DB::raw('DATE(stock_deliveries.created_at) as transaction_date'),
                            ])
                            ->groupBy('transaction_date')
                            ->orderBy('transaction_date', 'ASC')
                            ->first();
                // dd($receiving_item);

                $qty_issuing = StockDelivery::where(DB::raw('Date(stock_deliveries.date_received)'), $dateItem)
                ->join('stock_entries', 'stock_entries.id', 'stock_deliveries.stock_entry_id')
                ->whereIn('warehouse_id', ['892', '1385', '888'])
                ->where('stock_entries.project_id','<>', '333');
                                if($data['project'] != 'all') 
                                {
                                    $qty_issuing
                                    ->where('stock_deliveries.project_id', $data['project']); 
                                }
                                $qty_issuing
                                ->where('stock_deliveries.status','Received')
                                ->where('stock_deliveries.date_received', '<>', null);
                                $qty_issuing = $qty_issuing->select([
                                    DB::raw('count(stock_deliveries.id) as qty_issuing'),
                                    DB::raw('DATE(stock_deliveries.date_received) as transaction_date')
                                ])
                                ->groupBy('transaction_date')
                                ->orderBy('transaction_date', 'ASC')
                                ->first();

                if (!empty($receiving_item) || !empty($qty_issuing)) {
                    $receiving = !empty($receiving_item) ? $receiving_item->qty_receiving : 0;
                    $delivery = !empty($qty_issuing) ? $qty_issuing->qty_issuing : 0;
                    $closing = ($beginings + $receiving) - $delivery;
                    // $item = !empty($item) ? $item->id : 0;
                    $result = [
                        'date' => $dateItem,
                        'begining' => $beginings,
                        'receiving' => $receiving,
                        'delivery' => $delivery,
                        'closing' => $closing,
                        'project' => $data['project']
                    ];
                    $beginings = $closing;
                    array_push($collections, $result);
                }
            }
            // dd($collections);
        }

        if($submit == 1){
            if($data['project'] == 'all') {
                return redirect()->back()->with('error', 'Mohon Untuk memilih salah satu project ketika export');
            }
            // return Excel::download(new ReportExport($collections,2), 'StockMutationList_'.$data['date_from'].'_'.$data['date_to'].'.xlsx');
            return Excel::download(new GoodIssueMutationReportExport($data['project'],$data['date_from'], $data['date_to']), 'POD Report By no Good Isue-'.Carbon::now()->format('d-m-y').'.xlsx');
        }
        else{
            return view('report.goodIssueMutation', compact('show', 'data', 'search', 'item', 'collections', 'additionalError', 'additionalMessage'));
        }
    }

    public function stockDeliverMutationSKU(Request $request)
    {
        $projectId = session()->get('current_project')->id;
        $data['date_from'] = $request->input('date_from');
        $data['project'] = $request->input('project');

        $submit = $request->input('submit');

        $search = !empty($data['date_from']);

        $additionalMessage = '';
        $additionalError = true;
        

        // $item = Item::find($data['item']);
        // dd($item);
        // dd($data['item']);
        $collections = [];
        if ($search) {
            $show = 1;
            // sisa pengiriman awal didapatkan melalui nomor gi yang belum received tp sudah complete di gudang lebih kecil dari hari yang di filter
            // pengiiriman dibuat adalah ketika udah complete di gudang
            // pengiriman sampai adalah ketika sudah received
            // pengiriman akhir ketika dia di tanggal itu tp 

            $assign_to = AssignedTo::where('user_id', Auth::user()->id)->pluck('project_id');
            // dd($assign_to);
            if($data['project'] != 'all'){
                $item_finds = ItemProjects::where('project_id', $data['project'])->get();
            } else {
                $item_finds = ItemProjects::whereIn('project_id', $assign_to)->get();
            }
            // dd($item_finds);
        
    
            // dd($beginings);
            $periods = CarbonPeriod::create($data['date_from'], $data['date_from']);
            // dd(count($periods));
            $i = 0;
            $countPeriods = count($periods);
            // dd($collections);
            // Carbon::setWeekStartsAt(Carbon::SUNDAY);
            // Carbon::setWeekEndsAt(Carbon::SATURDAY);
            foreach ($periods as $period) {
                foreach ($item_finds as $key => $item_find) {
                    $akumulasi = 0;
                    // dd(Carbon::parse($dateItem)->startOfWeek());
                    // dd('$masuk_sini');
                    $dateItem = $period->format('Y-m-d');


                    $begining_delivery_all = StockDeliveryDetail::where(DB::raw('Date(stock_deliveries.created_at)'), '<' ,$data['date_from'])
                    ->join('stock_deliveries', 'stock_deliveries.id', 'stock_delivery_details.stock_delivery_id')
                    ->join('stock_entries', 'stock_entries.id', 'stock_deliveries.stock_entry_id')
                    ->where('stock_delivery_details.item_id', $item_find->item->id)
                    ->whereIn('warehouse_id', ['892', '1385', '888'])
                    ->where('stock_entries.project_id','<>', '333');
                            if($data['project'] != 'all') 
                            {
                                $begining_delivery_all
                                ->where('stock_deliveries.project_id', $data['project']); 
                            }
                            $begining_delivery_all->whereIn('stock_deliveries.status',['Received', 'Completed']);
                        // ->where('stock_deliveries.date_received', '<>', null);    
                    $begining_delivery_all = $begining_delivery_all->select([
                                'stock_deliveries.id as stock_delivery_id',
                            ])
                            ->sum('qty');
                    // dd($begining_delivery);
                    $receiving_item_all = StockDeliveryDetail::where(DB::raw('Date(stock_deliveries.date_received)'), '<' ,$data['date_from'])
                    ->join('stock_deliveries', 'stock_deliveries.id', 'stock_delivery_details.stock_delivery_id')
                    ->join('stock_entries', 'stock_entries.id', 'stock_deliveries.stock_entry_id')
                    ->where('stock_delivery_details.item_id', $item_find->item->id)
                    ->whereIn('warehouse_id', ['892', '1385', '888'])
                    ->where('stock_entries.project_id','<>', '333');
                            if($data['project'] != 'all') 
                            {
                                $receiving_item_all
                                ->where('stock_deliveries.project_id', $data['project']); 
                            }
                            $receiving_item_all->whereIn('stock_deliveries.status',['Received']);
                        // ->where('stock_deliveries.date_received', '<>', null);    
                    $receiving_item_all = $receiving_item_all->select([
                                'stock_deliveries.id as stock_delivery_id',
                            ])
                            ->sum('qty');
        

                    $beginings = $begining_delivery_all - $receiving_item_all;


                    $receiving_item = StockDeliveryDetail::where(DB::raw('Date(stock_deliveries.created_at)'), $dateItem)
                                ->join('stock_deliveries', 'stock_deliveries.id', 'stock_delivery_details.stock_delivery_id')
                                ->join('stock_entries', 'stock_entries.id', 'stock_deliveries.stock_entry_id')
                                ->where('stock_delivery_details.item_id', $item_find->item->id)
                                ->whereIn('warehouse_id', ['892', '1385', '888'])
                                ->where('stock_entries.project_id','<>', '333');

                                // ->where('stock_deliveries.project_id', $projectId)
                                if($data['project'] != 'all') 
                                {
                                    $receiving_item
                                    ->where('stock_deliveries.project_id', $data['project']); 
                                }
                                $receiving_item->whereIn('stock_deliveries.status',['Completed', 'Received']);
                                $receiving_item = $receiving_item->select([
                                    DB::raw('stock_deliveries.id, stock_delivery_details.item_id'),
                                    DB::raw('sum(stock_delivery_details.qty) as qty_receiving'),
                                    DB::raw('DATE(stock_deliveries.created_at) as transaction_date'),
                                ])
                                ->groupBy('transaction_date')
                                ->orderBy('transaction_date', 'ASC')
                                ->first();

                        $receiving_item_akumulasi = StockDeliveryDetail::where(DB::raw('Date(stock_deliveries.date_received)'),'<', Carbon::parse($dateItem))
                        ->join('stock_deliveries', 'stock_deliveries.id', 'stock_delivery_details.stock_delivery_id')
                        ->join('stock_entries', 'stock_entries.id', 'stock_deliveries.stock_entry_id')
                        ->where('stock_delivery_details.item_id', $item_find->item->id)
                        ->whereIn('warehouse_id', ['892', '1385', '888'])
                        ->where('stock_entries.project_id','<>', '333');

                        // ->where('stock_deliveries.project_id', $projectId)
                        if($data['project'] != 'all') 
                        {
                            $receiving_item_akumulasi
                            ->where('stock_deliveries.project_id', $data['project']); 
                        }
                        $receiving_item_akumulasi->whereIn('stock_deliveries.status',['Received']);
                        $receiving_item_akumulasi = $receiving_item_akumulasi->select([
                            DB::raw('stock_deliveries.id, stock_delivery_details.item_id'),
                            DB::raw('sum(stock_delivery_details.qty) as qty_akumulasi'),
                        ])
                    ->first();
                    // dd($receiving_item);

                    $qty_issuing = StockDeliveryDetail::where(DB::raw('Date(stock_deliveries.date_received)'), $dateItem)
                    ->join('stock_deliveries', 'stock_deliveries.id', 'stock_delivery_details.stock_delivery_id')
                    ->join('stock_entries', 'stock_entries.id', 'stock_deliveries.stock_entry_id')
                    ->whereIn('warehouse_id', ['892', '1385', '888'])
                    ->where('stock_delivery_details.item_id', $item_find->item->id)
                    ->where('stock_entries.project_id','<>', '333');
                                    if($data['project'] != 'all') 
                                    {
                                        $qty_issuing
                                        ->where('stock_deliveries.project_id', $data['project']); 
                                    }
                                    $qty_issuing
                                    ->where('stock_deliveries.status','Received')
                                    ->where('stock_deliveries.date_received', '<>', null);
                                    $qty_issuing = $qty_issuing->select([
                                        DB::raw('count(stock_deliveries.id), stock_delivery_details.item_id'),
                                        DB::raw('sum(stock_delivery_details.qty) as qty_issuing'),
                                        DB::raw('DATE(stock_deliveries.date_received) as transaction_date')
                                    ])
                                    ->groupBy('transaction_date')
                                    ->orderBy('transaction_date', 'ASC')
                                    ->first();

                    // if (!empty($receiving_item) || !empty($qty_issuing)) {
                        $receiving = !empty($receiving_item) ? $receiving_item->qty_receiving : 0;
                        $delivery = !empty($qty_issuing) ? $qty_issuing->qty_issuing : 0;
                        $akumulasi_all = !empty($receiving_item_akumulasi) ? $receiving_item_akumulasi->qty_akumulasi : 0;
                        $closing = ($beginings + $receiving) - $delivery;
                        // $item = !empty($item) ? $item->id : 0;
                        $result = [
                            'date' => $dateItem,
                            'begining' => $beginings,
                            'receiving' => $receiving,
                            'delivery' => $delivery,
                            // 'closing' => $closing,
                            'item' => $item_find->item->name,
                            'sku' => $item_find->item->sku,
                            'uom_name' => $item_find->item->default_uom->name,
                            'project' => $data['project'],
                            'akumulasi' => $akumulasi_all + $delivery
                        ];
                        $beginings = $closing;
                        array_push($collections, $result);
                    // }
                }
            }
            // dd($collections);
        }

        if($submit == 1){
            if($data['project'] == 'all') {
                return redirect()->back()->with('error', 'Mohon Untuk memilih salah satu project ketika export');
            }
            // return Excel::download(new ReportExport($collections,2), 'StockMutationList_'.$data['date_from'].'_'.$data['date_to'].'.xlsx');
            return Excel::download(new GoodIssueMutationSKUReportExport($data['project'],$data['date_from']), 'POD Report by SKU Item-'.Carbon::now()->format('d-m-y').'.xlsx');
        }
        else{
            return view('report.goodIssueMutationSKU', compact('show', 'data', 'search', 'item', 'collections', 'additionalError', 'additionalMessage'));
        }
    }

    public function detailGoodIssue($date, $project)
    {
        // dd('under maintance for you');
        // dd($project);
        $periods = CarbonPeriod::create($date, $date);
            // dd(count($periods) - 19);
        $i = 0;
        $countPeriods = count($periods);
        $begining_delivery = StockDelivery::where(DB::raw('Date(stock_deliveries.created_at)'), '<' ,$date)
            ->join('stock_entries', 'stock_entries.id', 'stock_deliveries.stock_entry_id')
            ->whereIn('warehouse_id', ['892', '1385', '888'])
            ->where('stock_entries.project_id','<>', '333');
                    if($project != 'all') 
                    {
                        $begining_delivery
                        ->where('stock_deliveries.project_id', $project); 
                    }
                    $begining_delivery->whereIn('stock_deliveries.status',['Completed', 'Received']);
                // ->where('stock_deliveries.date_received', '<>', null);
            $begining_delivery = $begining_delivery->select([
                        'stock_deliveries.id',
                        'stock_deliveries.code',
                        'stock_deliveries.consignee_id',
                        'stock_deliveries.created_at'
                    ])
                    ->with(['details', 'consignee'])->get();
           
            $receiving_item = StockDelivery::where(DB::raw('Date(stock_deliveries.date_received)'), '<' ,$date)
            ->join('stock_entries', 'stock_entries.id', 'stock_deliveries.stock_entry_id')
            ->whereIn('warehouse_id', ['892', '1385', '888'])
            ->where('stock_entries.project_id','<>', '333');
                    if($project != 'all') 
                    {
                        $receiving_item
                        ->where('stock_deliveries.project_id', $project); 
                    }
                    $receiving_item->where('stock_deliveries.status','Received');
                $receiving_item = $receiving_item->select([
                    'stock_deliveries.id',
                    'stock_deliveries.code',
                    'stock_deliveries.consignee_id',
                    'stock_deliveries.created_at'
                    ])
                    ->with(['details', 'consignee'])->get();
                    
            // dd($begining_delivery); 
            $a = $begining_delivery->unique()->diff($receiving_item->unique());

            // dd($a->toArray());
            // $collection = collect($begining_delivery, $receiving_item);
            // dd($collection);
            $unique_data = $a->all();
            // dd($collection);
            // dd($unique_data);
        $collections = [];
        foreach ($periods as $period) {
            $dateItem = $period->format('Y-m-d');
            $receiving_item = StockDelivery::where(DB::raw('Date(stock_deliveries.created_at)'), $dateItem)
                        ->join('stock_entries', 'stock_entries.id', 'stock_deliveries.stock_entry_id')
                        ->whereIn('warehouse_id', ['892', '1385', '888'])
                        ->where('stock_entries.project_id','<>', '333');
                        // ->where('stock_deliveries.project_id', $projectId)
                        if($project != 'all') 
                        {
                            $receiving_item
                            ->where('stock_deliveries.project_id', $project); 
                        }
                        $receiving_item->whereIn('stock_deliveries.status',['Completed', 'Received']);
                        $receiving_item = $receiving_item->select([
                            DB::raw('stock_deliveries.id'),
                            DB::raw('stock_deliveries.code as code'),
                            DB::raw('DATE(stock_deliveries.created_at) as transaction_date'),
                            'stock_deliveries.consignee_id',
                            'stock_deliveries.created_at',
                        ])
                        ->orderBy('transaction_date', 'ASC')
                        ->with(['details', 'consignee'])
                        // ->groupBy('stock_deliveries.id')
                        ->get();
            // dd($receiving_item);

            $qty_issuing = StockDelivery::where(DB::raw('Date(stock_deliveries.date_received)'), $dateItem)
            ->join('stock_entries', 'stock_entries.id', 'stock_deliveries.stock_entry_id')
            ->whereIn('warehouse_id', ['892', '1385', '888'])
            ->where('stock_entries.project_id','<>', '333');
                            if($project != 'all') 
                            {
                                $qty_issuing
                                ->where('stock_deliveries.project_id', $project); 
                            }
                            $qty_issuing
                            ->where('stock_deliveries.status','Received')
                            ->where('stock_deliveries.date_received', '<>', null);
                            $qty_issuing = $qty_issuing->select([
                                DB::raw('stock_deliveries.id'),
                                DB::raw('stock_deliveries.code as code'),
                                DB::raw('DATE(stock_deliveries.date_received) as transaction_date'),
                                'stock_deliveries.consignee_id',
                                'received_by',
                            ])
                            ->orderBy('transaction_date', 'ASC')
                            ->with(['details', 'consignee'])
                            ->groupBy('stock_deliveries.id')
                            ->get();

            if (!empty($receiving_item) || !empty($qty_issuing)) {
                $receiving = !empty($receiving_item) ? $receiving_item : '-';
                $delivery = !empty($qty_issuing) ? $qty_issuing : '-';
                // $item = !empty($item) ? $item->id : 0;
                $result = [
                    'date' => $dateItem,
                    'begining' => $delivery,
                    'receiving' => $receiving,
                    'begining_delivery' => $unique_data
                ];
                // $beginings = $closing;
                array_push($collections, $result);
            }
        }
        // dd($collections);
        return view('report.view_detail_mutation', compact('collections', 'date'));
    }

    public function handling($type = 'inbound', Request $request){
        
        $projectId = session()->get('current_project')->id;
        $data['date_from'] = $request->input('date_from');
        $data['date_to'] = $request->input('date_to');
        $data['warehouse_id'] = $request->input('warehouse_id');


        $submit = $request->input('submit');
        $search = !empty($data['date_from']) && !empty($data['date_to']);

        $additionalMessage = '';
        $additionalError = true;

        $user = User::find(Auth::user()->id);

        if($user->hasRole('WarehouseManager')){
            $warehouses = Warehouse::where('branch_id',$user->branch_id)->get();
        }
        elseif($user->hasRole('WarehouseSupervisor')){
            $warehouses = Warehouse::where('id', session()->get('warehouse_id'))->get();
        }
        elseif($user->hasRole('CargoOwner')){
            $warehouses = Warehouse::get();
        }

        if ($type == 'inbound') {
            if($user->hasRole('WarehouseSupervisor')){
                $collections = StockTransportDetail::join('stock_transports as st','st.id','=','stock_transport_details.stock_transport_id')
                ->where('st.type',$type)
                ->whereBetween('st.created_at', [$data['date_from'], $data['date_to']])
                ->where('st.status', 'Completed')
                ->where('st.project_id', $projectId)
                ->where('st.warehouse_id', $data['warehouse_id'])
                ->orderBy('st.id', 'desc')
                ->get(['stock_transport_details.created_at','st.code as item_code','stock_transport_details.item_id','st.advance_notice_id','stock_transport_details.qty','stock_transport_details.weight','stock_transport_details.volume','st.warehouse_id']);
            }
            elseif($user->hasRole('WarehouseManager')){
                $collections = StockTransportDetail::join('stock_transports as st','st.id','=','stock_transport_details.stock_transport_id')
                ->where('st.type',$type)
                ->whereBetween('st.created_at', [$data['date_from'], $data['date_to']])
                ->where('st.status', 'Completed')
                ->where('st.project_id', $projectId)
                ->where('st.consignee_id', $user->branch_id)
                ->where('st.warehouse_id', $data['warehouse_id'])
                ->orderBy('st.id', 'desc')
                ->get(['stock_transport_details.created_at','st.code as item_code','stock_transport_details.item_id','st.advance_notice_id','stock_transport_details.qty','stock_transport_details.weight','stock_transport_details.volume','st.warehouse_id']);
            }
            elseif($user->hasRole('CargoOwner') || $user->hasRole('Admin-BGR')){
                $collections = StockTransportDetail::join('stock_transports as st','st.id','=','stock_transport_details.stock_transport_id')
                ->where('st.type',$type)
                ->whereBetween('st.created_at', [$data['date_from'], $data['date_to']])
                ->where('st.status', 'Completed')
                ->where('st.project_id', $projectId)
                ->where('st.warehouse_id', $data['warehouse_id'])
                ->orderBy('st.id', 'desc')
                ->get(['stock_transport_details.created_at','st.code as item_code','stock_transport_details.item_id','st.advance_notice_id','stock_transport_details.qty','stock_transport_details.weight','stock_transport_details.volume','st.warehouse_id']);
            }
            // dd($collections);
        }else{
            
            if($user->hasRole('WarehouseSupervisor')){
                $collections = StockDeliveryDetail::join('stock_deliveries as sd','sd.id','=','stock_delivery_details.stock_delivery_id')
                ->join('stock_entries as se','se.id','=','sd.stock_entry_id')
                ->where('sd.type',$type)
                ->whereBetween('sd.created_at', [$data['date_from'], $data['date_to']])
                ->where('sd.status', 'Completed')
                ->where('sd.project_id', $projectId)
                ->where('se.warehouse_id', $data['warehouse_id'])
                ->orderBy('sd.id', 'desc')
                ->get(['stock_delivery_details.created_at','sd.code as item_code','stock_delivery_details.item_id','sd.stock_entry_id','stock_delivery_details.qty','stock_delivery_details.weight','stock_delivery_details.volume','se.warehouse_id']);
            }
            elseif($user->hasRole('WarehouseManager')){
                $collections = StockDeliveryDetail::join('stock_deliveries as sd','sd.id','=','stock_delivery_details.stock_delivery_id')
                ->join('stock_entries as se','se.id','=','sd.stock_entry_id')
                ->where('sd.type',$type)
                ->whereBetween('sd.created_at', [$data['date_from'], $data['date_to']])
                ->where('sd.status', 'Completed')
                ->where('sd.project_id', $projectId)
                ->where('sd.shipper_id', $user->branch_id)
                ->where('se.warehouse_id', $data['warehouse_id'])
                ->orderBy('sd.id', 'desc')
                ->get(['stock_delivery_details.created_at','sd.code as item_code','stock_delivery_details.item_id','sd.stock_entry_id','stock_delivery_details.qty','stock_delivery_details.weight','stock_delivery_details.volume','se.warehouse_id']);
            }
            elseif($user->hasRole('CargoOwner') || $user->hasRole('Admin-BGR')){
                $collections = StockDeliveryDetail::join('stock_deliveries as sd','sd.id','=','stock_delivery_details.stock_delivery_id')
                ->join('stock_entries as se','se.id','=','sd.stock_entry_id')
                ->where('sd.type',$type)
                ->whereBetween('sd.created_at', [$data['date_from'], $data['date_to']])
                ->where('sd.status', 'Completed')
                ->where('sd.project_id', $projectId)
                ->where('se.warehouse_id', $data['warehouse_id'])
                ->orderBy('sd.id', 'desc')
                ->get(['stock_delivery_details.created_at','sd.code as item_code','stock_delivery_details.item_id','sd.stock_entry_id','stock_delivery_details.qty','stock_delivery_details.weight','stock_delivery_details.volume','se.warehouse_id']);
            }

            
        }

        $total_qty = 0;
        $total_weight = 0;
        $total_volume = 0;
        $total_rates = 0;

        foreach ($collections as $key => $collection) {
            

            if ($type == 'inbound') {
                $advanceNotice = AdvanceNotice::find($collection->advance_notice_id);      
            }
            else{
                $stockEntry = StockEntry::find($collection->stock_entry_id);
                $advanceNotice = AdvanceNotice::find($stockEntry->stock_transport->advance_notice_id);
            }

            if($advanceNotice->activity->name == 'Opening Balance' || $advanceNotice->activity->name == 'Salah Kirim') {
                $collections->forget($key);
            }

            $contract = Contract::where('number_contract', $advanceNotice->contract_number)->first();

            $warehouse = Warehouse::find($collection->warehouse_id);

            $collection->item_activity_name = $advanceNotice->activity->name;
            $collection->item_warehouse_name = $warehouse->name;
            $collection->item_name = $collection->item->name;
            $collection->item_qty = $collection->qty;
            $collection->item_weight = $collection->weight;
            $collection->item_volume = $collection->volume;
            $collection->item_handling_tarif = $collection->item->handling_tarif;



            //$collection->label = $type == 'inbound' ? 'Good Receiving' : 'Good Issue';

            $total_qty = $total_qty + $collection->qty;
            $total_weight = $total_weight + $collection->weight;
            $total_volume = $total_volume + $collection->volume;

            
            

            if (!empty($contract)) {
                $collection->tarif = $type == 'inbound' ? $contract->tariff_handling_in : $contract->tariff_handling_out;

                if($contract->charge_method == 'Weight Based'){
                    $collection->charge = $collection->item_weight;
                    $collection->total_tarif = $collection->item_weight * $collection->tarif;
                }
                if($contract->charge_method == 'Volume Based'){
                    $collection->charge = $collection->item_volume;
                    $collection->total_tarif = $collection->item_volume * $collection->tarif;
                }
                if($contract->charge_method == 'Unit Based'){
                    $collection->charge = $collection->item_qty;
                    $collection->tarif = $collection->item_handling_tarif;
                    $collection->total_tarif = $collection->item_qty * $collection->tarif;
                }
                if($contract->charge_method == 'CW Based'){
                    if($collection->item_volume > $collection->item_weight ){
                        $collection->charge = $collection->item_volume;
                        $collection->total_tarif = $collection->item_volume * $collection->tarif;
                    }
                    if($collection->item_weight > $collection->item_volume ){
                        $collection->charge = $collection->item_weight;
                        $collection->total_tarif = $collection->item_weight * $collection->tarif;
                    }
                    
                }

            }

            unset($collection->warehouse_id);
            unset($collection->item_id);
            unset($collection->item);
            unset($collection->advance_notice_id);
            unset($collection->qty);
            unset($collection->weight);
            unset($collection->volume);
        }

        if($submit == 1){
            //return $collections;
            
            if($type == 'inbound'){
                return Excel::download(new ReportExport($collections,3), 'HandlingInList.xlsx');
            }
            else{
                return Excel::download(new ReportExport($collections,3), 'HandlingOutList.xlsx');
            }
        }
        else{
            //return $collections;
            return view('report.handling',compact('collections','type', 'data', 'search', 'additionalError', 'additionalMessage','total_qty','total_volume','total_weight','total_rates','warehouses'));
        }       
    }

    public function skuTransaction(Request $request) {
        $projectId = session()->get('current_project')->id;
        $stock = null;
        $stockInbound = null;
        $get_warehouse = null;
        // $items = Project::find($projectId)->items;
        $items = Item::leftJoin('item_projects', 'items.id', 'item_projects.item_id')->where('item_projects.project_id', $projectId)->select(['items.*'])->get();

        $data['item'] = $request->input('item');
        $data['date_from'] = $request->input('date_from');
        $data['date_to'] = $request->input('date_to');
        $submit = $request->input('submit');

        $search = !empty($data['date_from']) && !empty($data['date_to']);

        $additionalMessage = '';
        $additionalError = true;

        $user = User::find(Auth::user()->id);

        $manager_warehouse = Warehouse::where('branch_id', $user->branch_id)->pluck('id');

        if(empty($data['date_from']) && empty($data['date_to'])){
            $additionalMessage = 'Harap pilih tanggal mulai dan tanggal akhir';
        }

        $item = false;

        if($data['item']) {
            $item = Item::find($data['item']);    
        }
        $projectId = session()->get('current_project')->id;

        $collections = [];
        if ($search) {
            $collections = [];

            $stockEntryDetails = StockTransportDetail::join('stock_transports as st', 'st.id', '=', 'stock_transport_details.stock_transport_id')
                ->join('uoms as uo', 'uo.id', '=', 'stock_transport_details.uom_id')
                ->join('items', 'items.id', 'stock_transport_details.item_id');

            if($item) {
                $stockEntryDetails = $stockEntryDetails->where('stock_transport_details.item_id', $item->id);
            }
            // dd($item->id);
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
                DB::raw("sum(stock_entry_details.qty) as total_in, CONCAT(warehouses.id,'_',stock_entry_details.item_id) AS concat_id")
            ])
            ->join('parties', 'parties.id', 'warehouses.branch_id')
            ->join('stock_entries', 'stock_entries.warehouse_id', 'warehouses.id')
            ->join('projects', 'projects.id', 'stock_entries.project_id')
            ->join('stock_entry_details', 'stock_entry_details.stock_entry_id', 'stock_entries.id')
            ->join('items', 'items.id', 'stock_entry_details.item_id')
            ->join('uoms', 'uoms.id', 'items.default_uom_id')
            ->where('warehouses.is_active', 1)
            ->where('stock_entries.project_id',  $projectId)
            // ->where(DB::raw('Date(stock_entry_details.created_at)'),'>=' ,$request->date_from)
            // ->where(DB::raw('Date(stock_entry_details.created_at)'),'<=' ,$requet->date_to)
            ->where(DB::raw('Date(stock_entry_details.updated_at)'),'<=', $data['date_to'])
            ->where('stock_entries.status', 'Completed')
            ->where('stock_entries.type', 'inbound');
            $stock_ins = $stock_ins->where('stock_entry_details.item_id', $item->id)->sum('qty');
                // dd($stock_ins);
            // dd($stock_ins);
            $stock_outs = StockEntryDetail::select([
                DB::raw("sum(stock_entry_details.qty) as total_out"), 'stock_entry_details.item_id'
                , 'stock_entry_details.warehouse_id', 'stock_entries.project_id'
            ])
            ->join('stock_entries', 'stock_entries.id', 'stock_entry_details.stock_entry_id')
            ->where('stock_entries.status', 'Completed')
            ->where('stock_entries.type', 'outbound')
            ->where('stock_entry_details.item_id', $item->id)
            ->where('stock_entries.project_id',  $projectId)
            ->where(DB::raw('Date(stock_entry_details.updated_at)'),'<=', $data['date_to']);
            $stock_outs = $stock_outs->sum('qty');
            // dd($stock_outs);
            $stock = $stock_ins - $stock_outs;

            if($user->hasRole('WarehouseSupervisor')){
                $stockEntryDetails = $stockEntryDetails->whereBetween('stock_transport_details.updated_at', [$data['date_from'], $data['date_to']])
                ->where('st.type', 'inbound')
                ->where('st.project_id', $projectId)
                ->where('st.warehouse_id',session()->get('warehouse_id'))
                
                ->get([
                    'st.code',
                    'stock_transport_details.item_id as item_id',
                    'st.warehouse_id as warehouse_id',
                    'stock_transport_details.updated_at as control_date',
                    'stock_transport_details.qty',
                    'stock_transport_details.weight',
                    'stock_transport_details.volume',
                    'st.status as status',
                    'uo.name',
                    'st.type as type'
                ]);
            }
            elseif($user->hasRole('WarehouseManager')){
                $stockEntryDetails = $stockEntryDetails->whereBetween('stock_transport_details.updated_at', [$data['date_from'], $data['date_to']])
                ->where('st.type', 'inbound')
                ->where('st.project_id', $projectId)
                ->whereIn('st.warehouse_id',$manager_warehouse)
                ->get([
                    'st.code',
                    'stock_transport_details.item_id as item_id',
                    'st.warehouse_id as warehouse_id',
                    'stock_transport_details.updated_at as control_date',
                    'stock_transport_details.qty',
                    'stock_transport_details.weight',
                    'stock_transport_details.volume',
                    'st.status as status',
                    'uo.name',
                    'st.type as type'
                ]);   
            }
            elseif($user->hasRole('CargoOwner') || $user->hasRole('Admin-BGR')){
                $stockEntryDetails = $stockEntryDetails->whereBetween('stock_transport_details.updated_at', [$data['date_from'], $data['date_to']])
                ->where('st.type', 'inbound')
                ->where('st.project_id', $projectId)
                
                ->get([
                    'st.code',
                    'stock_transport_details.item_id as item_id',
                    'st.warehouse_id as warehouse_id',
                    'stock_transport_details.updated_at as control_date',
                    'stock_transport_details.qty',
                    'stock_transport_details.weight',
                    'stock_transport_details.volume',
                    'st.status as status',
                    'uo.name',
                    'st.type as type'
                ]);   
            }

            //dd($stockEntryDetails);

            $stockDeliveryDetails = StockDeliveryDetail::join('stock_deliveries as sd', 'sd.id', '=', 'stock_delivery_details.stock_delivery_id')
                ->join('uoms as uo', 'uo.id', '=', 'stock_delivery_details.uom_id')
                ->join('items', 'items.id', 'stock_delivery_details.item_id')
                ->join('stock_entries','stock_entries.id','sd.stock_entry_id');

            if($item) {
                $stockDeliveryDetails = $stockDeliveryDetails->where('stock_delivery_details.item_id', $item->id);
            }

            if($user->hasRole('WarehouseSupervisor')){
                $stockDeliveryDetails = $stockDeliveryDetails->whereBetween('stock_delivery_details.updated_at', [$data['date_from'], $data['date_to']])
                //->where('sd.type', 'outbound')
                ->where('sd.project_id', $projectId)
                ->where('stock_entries.warehouse_id',session()->get('warehouse_id'))
                ->orderBy('sd.id', 'asc')
                ->get([
                    'sd.code',
                    'stock_delivery_details.item_id as item_id',
                    'stock_entries.warehouse_id as warehouse_id',
                    'stock_delivery_details.updated_at as control_date',
                    'stock_delivery_details.qty',
                    'stock_delivery_details.weight',
                    'stock_delivery_details.volume',
                    'sd.status as status',
                    'uo.name',
                    'sd.type as type'
                ]);
                // dd($stockDeliveryDetails);

            }
            elseif($user->hasRole('WarehouseManager')){
                // dd('s');
                $stockDeliveryDetails = $stockDeliveryDetails->whereBetween('stock_delivery_details.updated_at', [$data['date_from'], $data['date_to']])
                //->where('sd.type', 'outbound')
                ->where('sd.project_id', $projectId)
                ->whereIn('stock_entries.warehouse_id',$manager_warehouse)
                ->select([
                    'sd.code',
                    'stock_delivery_details.item_id as item_id',
                    'stock_entries.warehouse_id as warehouse_id',
                    'stock_delivery_details.updated_at as control_date',
                    'stock_delivery_details.qty',
                    'stock_delivery_details.weight',
                    'sd.status as status',
                    'stock_delivery_details.volume',
                    'uo.name',
                    'stock_delivery_details.stock_delivery_id',
                    'sd.type as type'
                ])->orderBy('stock_delivery_details.stock_delivery_id', 'desc')->get();
                // dd($stockDeliveryDetails);
            }
            elseif($user->hasRole('CargoOwner') || $user->hasRole('Admin-BGR')){
                $stockDeliveryDetails = $stockDeliveryDetails->whereBetween('stock_delivery_details.updated_at', [$data['date_from'], $data['date_to']])
                //->where('sd.type', 'outbound')
                ->where('sd.project_id', $projectId)
                ->get([
                    'sd.code',
                    'stock_delivery_details.item_id as item_id',
                    'stock_entries.warehouse_id as warehouse_id',
                    'stock_delivery_details.updated_at as control_date',
                    'stock_delivery_details.qty',
                    'sd.status as status',
                    'stock_delivery_details.weight',
                    'stock_delivery_details.volume',
                    'uo.name',
                    'sd.type as type'
                ]);
            }

            //dd($stockDeliveryDetails);

            foreach ($stockEntryDetails as $stockEntryDetail) {
                array_push($collections, $stockEntryDetail);
            }
            // dd($collections);
            // dd($collections);

            foreach ($stockDeliveryDetails as $stockDeliveryDetail) {
                array_push($collections, $stockDeliveryDetail);
            }
            //$warehouse_id is dummy;
            foreach($collections as $collection){
                $get_warehouse[$collection->warehouse_id] = Warehouse::find($collection->warehouse_id);
            }
            
            // dd($collections);
            

            //dd($collections);
            
            // if($item) {
            //     $stockInbound = StockEntryDetail::join('stock_entries as se', 'se.id', '=', 'stock_entry_details.stock_entry_id')
            //                 ->join('items', 'items.id', 'stock_entry_details.item_id')
            //                 ->where('stock_entry_details.item_id', $item->id)
            //                 ->whereBetween('stock_entry_details.control_date', [$data['date_from'], $data['date_to']])
            //                 ->where('stock_entry_details.status', '<>', 'canceled')
            //                 ->where('se.type', 'inbound')
            //                 ->sum('qty');
            
            //     $stockInbound -= StockDeliveryDetail::join('stock_deliveries as se', 'se.id', '=', 'stock_delivery_details.stock_delivery_id')
            //                         ->join('items', 'items.id', 'stock_delivery_details.item_id')
            //                         ->where('stock_delivery_details.item_id', $item->id)
            //                         ->whereBetween('stock_delivery_details.created_at', [$data['date_from'], $data['date_to']])
            //                         ->sum('qty');
            // } else {
            //     $stockInbound = StockEntryDetail::join('stock_entries as se', 'se.id', '=', 'stock_entry_details.stock_entry_id')
            //                 ->join('items', 'items.id', 'stock_entry_details.item_id')
            //                 ->whereBetween('stock_entry_details.control_date', [$data['date_from'], $data['date_to']])
            //                 ->where('stock_entry_details.status', '<>', 'canceled')
            //                 ->where('se.type', 'inbound')
            //                 ->sum('qty');
            
            // $stockInbound -= StockDeliveryDetail::join('stock_deliveries as se', 'se.id', '=', 'stock_delivery_details.stock_delivery_id')
            //                     ->join('items', 'items.id', 'stock_delivery_details.item_id')
            //                     ->whereBetween('stock_delivery_details.created_at', [$data['date_from'], $data['date_to']])
            //                     ->sum('qty');
            // }

        }
        $index = 0;
        foreach($collections as $collection => $key){
            $key->item_index = $collection+1;
            $key->item_sku = $key->item->sku;
            $key->item_warehouse = Warehouse::find($key->warehouse_id)->name;
            $key->item_code = $key->code;
            $key->item_control_date = $key->control_date;
            $key->item_qty = $key->qty;
            $key->item_weight = $key->weight;
            $key->item_volume = $key->volume;
            $key->item_uom_name = $key->name;
            $key->status = $key->status;
            $key->type = $key->type;

            unset($key->item_id);
            unset($key->warehouse_id);
            unset($key->name);
            unset($key->qty);
            unset($key->weight);
            unset($key->volume);
            unset($key->control_date);
            unset($key->code);
            unset($key->item);
            // unset($key->type);
        }
    
        //  dd($collections->orderBy('control_date'));
        // $sorted = $collections->sortBy(function($col)
        // {
        //     return $col;
        // })->values()->all();
        // dd($sorted);
        if($submit == 1){
            //return $collections;
            return Excel::download(new ReportExport($collections,4), 'SkuTransactionList.xlsx');          
        }
        else{
            //return $collections;
            return view('report.skuTransaction', compact('data', 'items', 'stock','collections', 'search', 'stockInbound', 'item', 'additionalError', 'additionalMessage','get_warehouse'));
        }
    }

    public function estimatedDeliveryNote($type = 'outbound')
    {
        $collections = StockTransport::where('type',$type)
                        ->where('project_id', session()->get('current_project')->id)
                        ->orderBy('id', 'desc')
                        ->get();
        foreach ($collections as $key => $value) {
            foreach ($value->details as $key => $value_child) {
                    $value->qty += $value_child->qty;
                    $value->weight += $value_child->weight;
                    $value->volume += $value_child->volume;
            }
        }
            // dd($collections);
        return view('estimated_delivery_notes',compact('collections'));
    }

    public function estimatedDeliveryNotePrint($type = 'outbound')
    {
        $collections = StockTransport::where('type',$type)
                        ->where('project_id', session()->get('current_project')->id)
                        ->orderBy('id', 'desc')
                        ->get();
        foreach ($collections as $key => $value) {
            foreach ($value->details as $key => $value_child) {
                    $value->qty += $value_child->qty;
                    $value->weight += $value_child->weight;
                    $value->volume += $value_child->volume;
            }
        }
            // dd($collections);
        return view('estimated_delivery_notes_print',compact('collections'));
    }

    public function getStorages($warehouseId) {
        return Storage::where('warehouse_id', $warehouseId)->get();
    }

    public function getStoragesWarehouse(Request $request) {
        $storage = Storage::where('warehouse_id', $request->warehouse_id)->get();
        return response()->json($storage);
    }

    // api to storage mutation
    public function getwarehouseproject(Request $request)
    {
        $contractProject = ContractWarehouse::join('contracts', 'contracts.id', 'contract_warehouse.contract_id')
            ->join('warehouses', 'warehouses.id', 'contract_warehouse.warehouse_id')
            ->where('project_id', session()->get('current_project')->id)
            ->where('contracts.is_active', 1);
        
        if(Auth::user()->hasRole('WarehouseSupervisor')){
            $contractProject->where('warehouse_id', session()->get('warehouse_id'))
                ->with(['warehouse']);
        } elseif(Auth::user()->hasRole('WarehouseManager')) {
            $contractProject
            ->where('warehouses.branch_id', Auth::user()->branch->id)
            ->with(['warehouse']);
        }
        
        $contractProject = $contractProject->pluck('warehouses.name', 'warehouses.id');

        return response()->json($contractProject)->setCallback($request->input('callback'));
    }

    public function getwarehouse(Request $request)
    {
        $contractProject = ContractWarehouse::join('contracts', 'contracts.id', 'contract_warehouse.contract_id')
            ->select(['warehouses.id','total_weight', 'address', 'cities.name as city_name', 'warehouses.name', 'warehouses.code'])
            ->join('warehouses', 'warehouses.id', 'contract_warehouse.warehouse_id')
            ->join('cities', 'cities.id', 'warehouses.city_id')
            ->where('project_id', session()->get('current_project')->id)
            ->where('contracts.is_active', 1);
        
        if(Auth::user()->hasRole('WarehouseSupervisor')){
            $contractProject->where('warehouse_id', session()->get('warehouse_id'));
        } elseif(Auth::user()->hasRole('WarehouseManager')) {
            $contractProject
            ->where('warehouses.branch_id', Auth::user()->branch->id);
        }
        if($request->warehouse_id != null) {
            $contractProject->where('warehouse_id', $request->warehouse_id);
            $contractProject = $contractProject->first();

        } else{
            $contractProject = $contractProject->get();
        }
        

        return response()->json($contractProject)->setCallback($request->input('callback'));
    }

    public function getProject()
    {
        $assign_to = AssignedTo::select([
            'assigned_to.project_id', 'projects.name'
        ])->join('projects', 'projects.id', 'assigned_to.project_id')->where('user_id', Auth::user()->id)->where('projects.id', '<>', '333')->get();
        return response()->json($assign_to);
    }

    public function getItemProject(Request $request)
    {
        // $contractItem = Item::join('item_projects', 'items.id', 'item_projects.item_id')
        // ->where('item_projects.project_id', session()->get('current_project')->id);
        $warehouse_item = StockEntryDetail::join('stock_entries', 'stock_entries.id', 'stock_entry_details.stock_entry_id')
        ->join('items', 'items.id', 'stock_entry_details.item_id')
        ->where('stock_entries.project_id', session()->get('current_project')->id);
        if($request->warehouse_id != 0)
        {
            $warehouse_item
            ->where('stock_entries.warehouse_id', $request->warehouse_id);
        }
        $warehouse_item = $warehouse_item
            ->select(['items.sku','items.name', 'items.id'])
            ->groupBy('stock_entry_details.item_id')
            ->get();
        //     ->toSql();
        // dd($contractItem, session()->get('current_project')->id, $request->warehouse_id);
        return response()->json($warehouse_item)->setCallback($request->input('callback'));
    }
    
    // aincomplete
    public function ainaoncomplete(Request $request)
    {
        $message = NULL;
        $collections = null;
        $data['branch_id'] = $request->branch;
        $data['warehouse_id'] = $request->warehouse;
        $data['type'] = $request->type ? $request->type :  '';
        
        $search = !empty($data['branch_id']) && !empty($data['type']); 
        // 1. Kalau ada data cabang, filter datanya
        $collections = [];
        $detail_advance_notices = [];
        if(!Auth::user()->hasRole('CargoOwner') && $search != null ) {
            $show = 1;
            // 1. Kalau inbound, keluarkan data dimana consignee equals to user branch data
            $collections = AdvanceNotice::where('type',$data['type'])
                    ->select(['user_id','id','code','origin_id','destination_id','consignee_id','shipper_id',
                    'etd','eta','status','user_id2','warehouse_id'])  
                    ->where('status', 'Completed');
                    if($data['warehouse_id'] != 0){
                        $collections = $collections
                        ->where('warehouse_id', $data['warehouse_id']);
                    }
                    $collections
                    ->orderBy('id', 'desc');
                    
                    // ->get(['user_id','id','code','origin_id','destination_id','consignee_id','shipper_id','etd','eta','status','employee_name']);

            if($data['type'] == 'inbound') {
                // $collections = $collections->filter(function ($item) {
                //     return $item->consignee_id == Auth::user()->branch->id;
                // });
                $collections = $collections->where('consignee_id', $data['branch_id']);

            } else {
                // 2. Kalau outbound, keluarkan data dimana shipper equals to user branch data
                // $collections = $collections->filter(function ($item) {
                //     return $item->shipper_id == Auth::user()->branch->id;
                // });
                $collections = $collections->where('shipper_id', $data['branch_id']);

            }

            $collections = $collections->get();
            if($collections) {
                //hitung outstanding secara keseluruhan
                foreach ($collections as $collection) {
                    $total_items = $collection->details->sum('qty');
                    $delivered_items = 0;
                    $transports = $collection->transports()->ofStatus(['Completed'])->get();
                    foreach ($transports as $transport) {
                        if($data['type']=='inbound'){
                            $delivered_items += $transport->details->sum('qty');
                        }
                        else{
                            $delivered_items += $transport->details->sum('plan_qty');
                        }
                    }
                    $collection->outstanding = $total_items - $delivered_items;
                    
                    //hitung outstanding per detail item
                    $detail_advance_notices[$collection->id] = AdvanceNoticeDetail::where('stock_advance_notice_id',$collection->id)->get();
    
                    foreach($detail_advance_notices[$collection->id] as $detail){
                        $inbound = AdvanceNoticeDetail::join('stock_advance_notices as san', 'san.id', '=', 'stock_advance_notice_details.stock_advance_notice_id')
                        //->where('san.status', 'Closed')
                        ->where([
                            'stock_advance_notice_details.item_id' => $detail->item_id,
                            'stock_advance_notice_details.ref_code' => $detail->ref_code,
                            'san.id' => $collection->id,
                        ])
                        ->sum('stock_advance_notice_details.qty');
                        
    
                        if($data['type'] == 'inbound'){
                            $outbound_completed = StockTransportDetail::join('stock_transports as st', 'st.id', '=', 'stock_transport_details.stock_transport_id')
                            ->where('st.status', 'Completed')
                            ->where([
                                'stock_transport_details.item_id' => $detail->item_id,
                                'stock_transport_details.ref_code' => $detail->ref_code,
                                'st.advance_notice_id' => $collection->id
                            ])
                            ->sum('stock_transport_details.qty');
                        
                        }
                        //ketika dp, data plan adalah actual
                        else{
                            $outbound_completed = StockTransportDetail::join('stock_transports as st', 'st.id', '=', 'stock_transport_details.stock_transport_id')
                            ->where('st.status', 'Completed')
                            ->where([
                                'stock_transport_details.item_id' => $detail->item_id,
                                'stock_transport_details.ref_code' => $detail->ref_code,
                                'st.advance_notice_id' => $collection->id
                            ])
                            ->sum('stock_transport_details.plan_qty');
                        }
    
                        $outbound_incompleted = StockTransportDetail::join('stock_transports as st', 'st.id', '=', 'stock_transport_details.stock_transport_id')
                            ->where('st.status', 'Processed')
                            ->where([
                                'stock_transport_details.item_id' => $detail->item_id,
                                'stock_transport_details.ref_code' => $detail->ref_code,
                                'st.advance_notice_id' => $collection->id
                            ])
                            ->sum('stock_transport_details.plan_qty');
    
                            //return $stockTransport->advance_notice_id;
    
                        $detail->detail_outstanding = $inbound - ($outbound_completed + $outbound_incompleted);
                    }
                }
            }
        } else {
            $show = 0;
        }

        //untuk keperluan modal detail outstanding
        

        if($request->get('submit')){
            return Excel::download(new AdvanceNoticeExport($collections,$data['type']), $data['type'].'List-.xlsx');
        }
        else{
            if( !empty($search)) {
            //return $collections;
             //merapikan struktur array untuk di convert to excel
                foreach($collections as $collection){

                    $collection->item_code = $collection->code;

                    if($data['type']=='inbound'){
                        $collection->item_storage_area = $collection->consignee['name'];
                    }
                    else{
                        $collection->item_storage_area = $collection->shipper['name'];
                    }

                    $collection->item_origin = $collection->origin->name;
                    $collection->item_destination = $collection->destination->name;

                        
                    $collection->item_etd = $collection->etd;
                    $collection->item_eta = $collection->eta;

                    $collection->item_outstanding = $collection->outstanding;
                    
                    $collection->item_status = $collection->status;
                    $collection->item_employee_name = $collection->employee_name;
                    

                    unset($collection->code);
                    unset($collection->consignee_id);
                    unset($collection->shipper_id);
                    unset($collection->origin_id);
                    unset($collection->destination_id);
                    unset($collection->warehouse_id);
                    unset($collection->consignee);
                    unset($collection->shipper);
                    unset($collection->origin);
                    unset($collection->destination);
                    unset($collection->details);
                    unset($collection->etd);
                    unset($collection->eta);
                    //unset($collection->status);
                    unset($collection->employee_name);
                    unset($collection->outstanding);
                }
            }
            // dd($collections[0]->header);
            return view('report.stockAinAon')->with([
                'search' => $search,
                'collections' => $collections,
                'detail_advance_notices' => $detail_advance_notices,
                // 'type' => $data['type'],
                'data' => $data
            ]);
        }
    }
    
    public function branch()
    {
        $user = \App\User::find(Auth::user()->id);
        $branch = Party::select(['parties.name', 'parties.id'])
            ->join('parties_party_types', 'parties_party_types.party_id', 'parties.id')
            ->where('party_type_id','3');
        
        if($user->hasRole('WarehouseManager')) {    
            $branch->where('parties.id', $user->branch_id);
        }
            
        $branch = $branch->get();
        return response()->json($branch);
    }
    
    public function warehouse_list(Request $request)
    {
        // $warehouse = Warehouse::select(['name', 'code', 'id'])
        //     ->where('is_active',1);
        // if(Auth::user()->hasRole('WarehouseManager'))
        // {
        //     $warehouse->where('branch_id', Auth::user()->branch_id);

        // } else {
            $warehouse = ContractWarehouse::join('contracts', 'contracts.id', 'contract_warehouse.contract_id')
            ->join('warehouses', 'warehouses.id', 'contract_warehouse.warehouse_id')
            ->where('project_id', session()->get('current_project')->id);
            if(Auth::user()->hasRole('WarehouseSupervisor'))
            {
                if(session()->get('current_project')->id == 337) {
                    $warehouse->where('warehouses.id', session()->get('warehouse_id'));
                }
            }
            $warehouse->where('contracts.is_active', 1);
            if(Auth::user()->hasRole('WarehouseManager')) {
                $warehouse->where('warehouses.branch_id', Auth::user()->branch->id);
            }
            $warehouse->select(['warehouses.name', 'warehouses.code', 'warehouses.id']);
        // }
        $warehouse = $warehouse->get();

        return response()->json($warehouse);
    }
    
    public function projectManagement(Request $request)
    {
        $data['branch'] = $request->input('branch');
        // $data['status'] = $request->input('status');

        $submit = $request->input('submit');

        $search = !empty($data['branch']);

        $additionalMessage = '';
        $additionalError = true;

        if(empty($data['branch'])){
            $additionalMessage = 'Harap pilih Branch';
        }
        if($request->submit == 1){
            // dd(app('App\Http\Controllers\ReportApiController')->projectManagement($request));
            // return Excel::download(new ReportExport(app('App\Http\Controllers\ReportApiController')->projectManagement($request),12), 'Report Project.xlsx');
            try {
                return Excel::download(new ReportExport(app('App\Http\Controllers\ReportApiController')->projectManagement($request)['data'],12), 'Report Project.xlsx');
            } catch (\Exception $e) {
                dd($e->getMessage());
            }
           
        
        }else {
            return view('report.projectWarehouse')->with(['search' => $search, 'data' => $data]);
        }
    }

    public function contractManagement(Request $request)
    {
        $data['branch'] = $request->input('branch');
        $data['status'] = $request->input('status');
        $data['operation'] = $request->input('operation');

        $submit = $request->input('submit');

        $search = !empty($data['branch']) && !empty($data['status']);

        $additionalMessage = '';
        $additionalError = true;
     
        if($request->submit == 1){
            // return Excel::download(new ReportExport(app('App\Http\Controllers\ReportApiController')->contractManagement($request),11), 'Report Contract.xlsx');
            try {
            return Excel::download(new ReportExport(app('App\Http\Controllers\ReportApiController')->contractManagement($request)['data'],11), 'Report Contract.xlsx');
            }
            catch (\Exception $e) {
                dd($e->getMessage());
            }
        }else {
            return view('report.contractWarehouse')->with([
                'data' => $data,
                'search' => $search
            ]);
        }
    }

    public function goodreceiveManagement(Request $request)
    {
        // $deliveries = StockDelivery::select('stock_deliveries.code', 'stock_deliveries.id', 'stock_deliveries.ref_code')
        // // ->where('warehouse_id', '')
        // ->join('stock_entries', 'stock_entries.id', 'stock_deliveries.stock_entry_id')
        // ->where('warehouse_id', '892')->get();
        // return vie('report.good_issue_report')->with([
        //     'deliveries' => $deliveries
        // ]);

        $data['project'] = $request->input('project');

        $submit = $request->input('submit');

        $search = !empty($data['project']);

        $additionalMessage = '';
        $additionalError = true;
     
        if($request->submit == 1){
            if($data['project'] == 'all') {
                return redirect()->back()->with('error', 'Mohon Untuk memilih salah satu project ketika export');
            }
            // return Excel::download(new ReportExport(app('App\Http\Controllers\ReportApiController')->contractManagement($request),11), 'Report Contract.xlsx');
            try {
                return Excel::download(new GoodReceiveReportExport($data['project']),'List Outbound-'.Carbon::now()->format('d-m-y').'.xlsx');
                // return Excel::download(new ReportExport(app('App\Http\Controllers\ReportApiController')->goodreceiveManagement($request)['data'],11), 'Report Good Issue.xlsx');
            }
            catch (\Exception $e) {
                dd($e->getMessage());
            }
        }else {
            return view('report.good_issue_report')->with([
                'data' => $data,
                'search' => $search
            ]);
        }
    }
    
    public function detailInbound(Request $request)
    {
        $data['warehouse_id'] = $request->input('warehouse_id');
        $data['date_from'] = $request->date_from;
        $data['date_to'] = $request->input('date_to');
        $data['item'] = $request->input('item');
        $additionalMessage = '';
        $additionalError = true;

        $submit = $request->input('submit');
        $warehouse = ContractWarehouse::join('contracts', 'contracts.id', 'contract_warehouse.contract_id')
        ->join('warehouses', 'warehouses.id', 'contract_warehouse.warehouse_id')
        ->join('projects', 'projects.id', 'contracts.project_id')
        ->join('parties', 'parties.id', 'warehouses.branch_id')
        ->where('contracts.project_id', session()->get('current_project')->id)
        ->where('contracts.is_active', 1)
        ->where('warehouses.id', $request->input('warehouse_id'))
        ->where('warehouses.branch_id', Auth::user()->branch->id)
        ->select(['warehouses.name', 'warehouses.code', 'warehouses.id', 'projects.name as project_name', 'parties.name as branch_name'])->first();
        $item = [];
        if($data['item'] != 'all')
        {
            $item = Item::find($data['item']);
        }
        $search = !empty($data['warehouse_id']) && !empty($data['date_to']) && !empty($data['date_from'] && !empty($data['item']));

        // dd('under maintance');
        // $type = 'inbound';
        // $projectId = session()->get('current_project')->id;
        // dd(session()->get('current_project')->name);
        // dd(StockTransportDetail::all());
        // dd($item);
        if($request->submit == '1') {
            return Excel::download(new StockTransportDetailExport($data['date_from'], $data['date_to'], $data['item'], $data['warehouse_id']), 'List Inbound-'.Carbon::now()->format('d-m-y').'.xlsx');
        } else {
            return view('report.detailInbound')->with([
                'data' => $data,
                'search' => $search,
                'additionalMessage' => $additionalMessage,
                'additionalError' => $additionalError,
                'warehouse' => $warehouse,
                'item' => $item
            ]);
        }
    }

    public function warehouse_stockOpname(Request $request)
    {
        $warehouse = \App\StockOpname::select(['warehouses.id', 'warehouses.name', 'warehouses.code'])->join('warehouses', 'warehouses.id', 'stock_opnames.warehouse_id');
        if(Auth::user()->hasRole('WarehouseManager'))
        {
            $warehouse->where('branch_id', Auth::user()->branch_id);
        }

        $warehouse = $warehouse->groupBy('warehouse_id')->where('warehouses.branch_id', $request->branch_id)->get();

        return response()->json($warehouse);
    }
    
    public function stockOpnames(Request $request)
    {
        $data['warehouse'] = $request->input('warehouse');
        $data['date_from'] = $request->date_from;
        $data['date_to'] = $request->input('date_to');
        $data['branch_id'] = $request->input('branch');

        $search = !empty($data['warehouse']);
        $stock_opnames = [];
        if($search){
            $stock_opnames = StockOpname::select([
                'date',
                'calculated_by',
                'status',
                'id',
                'warehouse_id'
            ]);
            if($data['warehouse'] != '0') {
               $stock_opnames->where('warehouse_id', $data['warehouse']);
            }
            $stock_opnames = $stock_opnames->with('warehouse')->get();
        }
        return view('report.stockOpnames')->with([
            'data' => $data,
            'search' => $search,
            'stock_opnames' => $stock_opnames
        ]);
    }

    public function detailOutbound(Request $request)
    {
        // dd('under maintance');
        $data['warehouse_id'] = $request->input('warehouse_id');
        $data['date_from'] = $request->date_from;
        $data['date_to'] = $request->input('date_to');
        $data['item'] = $request->input('item');
        $additionalMessage = '';
        $additionalError = true;

        $submit = $request->input('submit');
        $warehouse = ContractWarehouse::join('contracts', 'contracts.id', 'contract_warehouse.contract_id')
        ->join('warehouses', 'warehouses.id', 'contract_warehouse.warehouse_id')
        ->join('projects', 'projects.id', 'contracts.project_id')
        ->join('parties', 'parties.id', 'warehouses.branch_id')
        ->where('contracts.project_id', session()->get('current_project')->id)
        ->where('contracts.is_active', 1)
        ->where('warehouses.id', $request->input('warehouse_id'))
        ->where('warehouses.branch_id', Auth::user()->branch->id)
        ->select(['warehouses.name', 'warehouses.code', 'warehouses.id', 'projects.name as project_name', 'parties.name as branch_name'])->first();
        $item = [];
        if($data['item'] != 'all')
        {
            $item = Item::find($data['item']);
        }
        $search = !empty($data['warehouse_id']) && !empty($data['date_to']) && !empty($data['date_from'] && !empty($data['item']));
        if($request->submit == '1') {
            return Excel::download(new StockDeliveryDetailExport($data['date_from'], $data['date_to'], $data['item'], $data['warehouse_id']), 'List Outbound-'.Carbon::now()->format('d-m-y').'.xlsx');
        }else {
            return view('report.detailOutbound')->with([
                'data' => $data,
                'search' => $search,
                'additionalMessage' => $additionalMessage,
                'additionalError' => $additionalError,
                'warehouse' => $warehouse,
                'item'  => $item
            ]);
        }
    }

    public function getStorageWarehouseDetail(Request $request) 
    {
        $masterData = \App\StockEntryDetail::select(['storages.id', 'storages.code'])->join('stock_entries as se','se.id','=',
        'stock_entry_details.stock_entry_id')
        ->join('storages', 'storages.id', 'stock_entry_details.storage_id')
        ->where('se.project_id', session()->get('current_project')->id)
        ->where('se.status','Completed');
        $storages = (clone $masterData)
        ->where('storages.warehouse_id', $request->warehouse_id);
        if($request->item_id != 'all' && $request->item_id != null) {
            $storages
            ->where('stock_entry_details.item_id', $request->item_id);
        }
        $storages = $storages
        ->where('se.type', 'inbound')
        ->groupBy('stock_entry_details.storage_id')
        ->get();

        return response()->json($storages);   
    }

    public function getControlDate(Request $request)
    {
        $masterData = \App\StockEntryDetail::join('stock_entries as se','se.id','=',
        'stock_entry_details.stock_entry_id')
        ->where('se.project_id', session()->get('current_project')->id)
        ->where('se.status','Completed');
        $control_dates = (clone $masterData)
        ->where('se.warehouse_id', $request->warehouse_id);
        if($request->item_id != 'all' && $request->item_id != null) {
            $control_dates
            ->where('stock_entry_details.item_id', $request->item_id);
        }
        $control_dates = $control_dates
        ->where('se.type', 'inbound')
        ->groupBy('stock_entry_details.control_date')
        ->pluck('stock_entry_details.control_date');  

        return response()->json($control_dates);
    }

    public function stockMutationLocationDetail(Request $request)
    {
        // dd($request);
        // dd($request->input('date_from'));
        if(!(Auth::user()->hasRole('WarehouseManager') || Auth::user()->hasRole('WarehouseSupervisor')|| Auth::user()->hasRole('Admin-BGR') || Auth::user()->hasRole('SPI') || Auth::user()->hasRole('Reporting'))) {
            abort(403);
        }

        $data['warehouse_id'] = $request->input('warehouse_id');
        $data['item'] = $request->input('item');
        $data['group_by'] = $request->input('group_by');
        $data['result'] = $request->input('result');


        // dd($data['storage']);
        // dd($data['item']);
        $fix_arr = [];
        $a = [];
        if($data['warehouse_id'] != null) {
            $from = $request->date_from;
            $to = $request->date_to;
            $projectId = session()->get('current_project')->id;
            // dd($from, $to);
            
            // begining
            $stock_ins = Warehouse::select([
                'parties.name as branch_name',
                'warehouses.name as warehouse_name',
                'warehouses.id as warehouse_id',
                'projects.name as project_name',
                'items.sku',
                'stock_entry_details.ref_code',
                'items.weight',
                'warehouses.total_weight as capacity',
                'items.name as name_sku',
                'uoms.name as uom_name',
                'items.id as item_id',
                'stock_entry_details.control_date',
                'stock_entry_details.storage_id as storage_id',
                'warehouses.ownership as status_gudang',
                'stock_entries.project_id',
                'storages.code as storage_code',
                DB::raw("sum(stock_entry_details.qty) as total_in, CONCAT(stock_entry_details.item_id, '_',warehouses.id,'_', stock_entries.project_id, '_',stock_entry_details.control_date,'_',stock_entry_details.storage_id, '_', stock_entry_details.ref_code) AS concat_id")
            ])
            ->join('parties', 'parties.id', 'warehouses.branch_id')
            ->join('stock_entries', 'stock_entries.warehouse_id', 'warehouses.id')
            ->join('projects', 'projects.id', 'stock_entries.project_id')
            ->join('stock_entry_details', 'stock_entry_details.stock_entry_id', 'stock_entries.id')
            ->join('items', 'items.id', 'stock_entry_details.item_id')
            ->join('uoms', 'uoms.id', 'items.default_uom_id')
            ->join('storages', 'stock_entry_details.storage_id', 'storages.id')
            ->where('warehouses.is_active', 1)
            ->where('stock_entries.project_id', session()->get('current_project')->id)
            // ->where(DB::raw('Date(stock_entry_details.created_at)'),'>=' ,$request->date_from)
            // ->where(DB::raw('Date(stock_entry_details.created_at)'),'<=' ,$requet->date_to)
            ->where('stock_entries.status', 'Completed')
            ->where('stock_entries.type', 'inbound');
            if($data['warehouse_id'] != 'all') {
                $stock_ins
                ->where('stock_entries.warehouse_id', $data['warehouse_id']);
            }
            if($data['item'] != 'all') {
                $stock_ins
                ->where('stock_entry_details.item_id', $data['item']);
            }
            if($data['group_by'] == 'storage' && $data['result'] != 'all') {
                $stock_ins
                ->where('stock_entry_details.storage_id', $data['result']);
            }
            
            if($data['group_by'] == 'control_date' && $data['result'] != 'all') {
                $stock_ins
                ->where(DB::raw('Date(stock_entry_details.control_date)'), $data['result']);
            }
            if(Auth::user()->hasRole('WarehouseManager') && $data['warehouse_id'] == 'all') {
                $warehouses = Warehouse::where('branch_id', Auth::user()->branch_id)->pluck('id');
                // dd($warehouses);
                $stock_ins
                    ->whereIn('stock_entries.warehouse_id', $warehouses);
            }
            $stock_ins = $stock_ins->groupBy('concat_id')
            ->get();
            // dd($stock_ins);
            $stock_outs = StockEntryDetail::select([
                DB::raw("sum(stock_entry_details.qty) as total_out, CONCAT(stock_entry_details.warehouse_id,'_',stock_entry_details.item_id,'_', stock_entries.project_id,'_',stock_entry_details.control_date,'_',stock_entry_details.storage_id, '_', stock_entry_details.ref_code) AS m_concat_id"), 'stock_entry_details.item_id'
                , 'stock_entry_details.warehouse_id', 'stock_entries.project_id',
                'stock_entry_details.control_date', 'stock_entry_details.storage_id', 'stock_entry_details.ref_code'
            ])
            ->join('stock_entries', 'stock_entries.id', 'stock_entry_details.stock_entry_id')
            ->where('stock_entries.status', 'Completed')
            ->where('stock_entries.project_id', session()->get('current_project')->id)
            ->where('stock_entries.type', 'outbound');

            if($data['warehouse_id'] != 'all') {
                $stock_outs
                ->where('stock_entries.warehouse_id', $data['warehouse_id']);
            }
            if($data['item'] != 'all') {
                $stock_outs
                ->where('stock_entry_details.item_id', $data['item']);
            }
            if($data['group_by'] == 'storage' && $data['result'] != 'all') {
                $stock_outs
                ->where('stock_entry_details.storage_id', $data['result']);
            }
            if($data['group_by'] == 'control_date' && $data['result'] != 'all') {
                $stock_outs
                ->where(DB::raw('Date(stock_entry_details.control_date)'), $data['result']);
            }
            $stock_outs = $stock_outs->groupBy('m_concat_id')->get();
            // dd($stock_outs);

            $arr_out = [];
            foreach ($stock_outs as $out) {
            $arr_out[$out->item_id."_".$out->warehouse_id."_".$out->project_id."_".$out->control_date."_".$out->storage_id."_".$out->ref_code]= $out->total_out;
            }
            // dd($stock_ins);
            // dd($arr_out["1007_1415_1_2020-04-16_2520"]['total_out']);
            // dd()
            $arr_by_gudang = [];
            foreach ($stock_ins as $in) {
                // dd($in->item_id."_".$in->warehouse_id."_".$in->project_id."_".$in->control_date."_".$in->storage_id);
                if (!empty($arr_out[$in->item_id."_".$in->warehouse_id."_".$in->project_id."_".$in->control_date."_".$in->storage_id."_".$in->ref_code])){
                    // dd('masuk dong');
                    $qty_out = $arr_out[$in->item_id."_".$in->warehouse_id."_".$in->project_id."_".$in->control_date."_".$in->storage_id."_".$in->ref_code];
                   

                }else{
                    $qty_out = 0;
                }
                $total_out = $qty_out;
                // print_r($qty_out);
                $stock = ($in->total_in) - $qty_out;

                // dd($stock_netto);
                if($stock > 0) {
                
                    $a[$data['group_by'] == 'storage' ? $in->storage_code : $in->control_date]['item'][] = [
                        'sku'    =>$in->sku,
                        'control_date'    =>$in->control_date,
                        'sku_name' => $in->name_sku,
                        'status_gudang' =>$in->status_gudang,
                        'ref_code' => $in->ref_code,
                        'capacity' => number_format(round($in->capacity), 2, ',', '.'),
                        'stock' => number_format($stock, 2, ',', '.'),
                        'uom_name' => $in->uom_name,
                        'keterangan' => $stock == 0 ? 'Kosong' : 'Masih Tersedia',
                        'storage'   => $in->storage_code,
                        ];
                    $a[$data['group_by'] == 'storage' ? $in->storage_code : $in->control_date]['detail'] = [
                        'branch' => $in->branch_name,
                        'warehouse' => $in->warehouse_name,
                        'project'   => $in->project_name,
                        'storage'   => $in->storage_code,
                        'ref_code' => $in->ref_code,
                        'count' => count($a[$data['group_by'] == 'storage' ? $in->storage_code : $in->control_date]['item'])
                    ];
                }
                
                // array_merge($a, $new);
                // array_push($fix_arr, $a);
                //   $fix_arr->push($a);
                
            }
            // dd($a);
            // dd($qty_out);
        }
        $submit = $request->input('submit');

        $warehouse = ContractWarehouse::join('contracts', 'contracts.id', 'contract_warehouse.contract_id')
            ->join('warehouses', 'warehouses.id', 'contract_warehouse.warehouse_id')
            ->join('projects', 'projects.id', 'contracts.project_id')
            ->join('parties', 'parties.id', 'warehouses.branch_id')
            ->where('contracts.project_id', session()->get('current_project')->id)
            ->where('contracts.is_active', 1)
            ->where('warehouses.id', $request->input('warehouse_id'));
            if(Auth::user()->hasRole('WarehouseSupervisor') || (Auth::user()->hasRole('WarehouseManager'))){
                $warehouse->where('warehouses.branch_id', Auth::user()->branch->id);
             }
        $warehouse = $warehouse
            ->select(['warehouses.name', 'warehouses.code', 'warehouses.id', 'projects.name as project_name', 'parties.name as branch_name'])->first();

        $search = !empty($data['warehouse_id']);
        // dd($search);
        $additionalMessage = '';
        $additionalError = true;
     
        if($request->submit == 1){
            // return Excel::download(new ReportExport(app('App\Http\Controllers\ReportApiController')->contractManagement($request),11), 'Report Contract.xlsx');
            try {
                if($data['warehouse_id'] == 'all') {
                    return redirect()->back()->withErrors('Silahkan pilih salah satu gudang');
                }
                return Excel::download(new StockOnLocationDetailReport($data['item'], $data['warehouse_id'], $data['group_by'], $data['result']), 'Laporan Stock Per Lokasi Detail-'.Carbon::now()->format('d-m-y').'.xlsx');
            }
            catch (\Exception $e) {
                dd($e->getMessage());
            }
        }else{ 
            // dd($fix_arr[0][]);
            return view('report.stockLocationDetail')->with([
                'data' => $data,
                'search' => $search,
                'additionalMessage' => $additionalMessage,
                'additionalError' => $additionalError,
                'warehouse' => $warehouse,
                'collections'  => $a
            ]);
        }
        
    }

    public function stockMutationMonth(Request $request)
    {
        $data['date_from'] = $request->date_from;
        if($request->date_from) {
            
            $request->validate([
                'date_from' => 'date_format:"F Y"'
            ]);

            if($request->submit == 2) {
                return Excel::download(new MutationMonthReportExport($request->date_from), 'Laporan Stock Mutasi Per Bulan-'.Carbon::now()->format('d-m-y').'.xlsx');
            } else {

                $changeFormat = Carbon::parse($request->date_from);
            
                $firstDate = clone $changeFormat->firstOfMonth(); 
    
                $end_date = clone $changeFormat->endOfMonth(); 
    
                $data['date_difference'] = $end_date->diffInDays($firstDate, true) + 1;
    
                $now = Carbon::now();
                if(($firstDate->month > $now->month && $firstDate->year > $now->year) || ($firstDate->month < $now->month && $firstDate->year > $now->year)) {
                    return redirect()->back()->withErrors('Silahkan pilih bulan yang sedang berjalan');
                }
    
                // dd($now->year == $firstDate->year ? true: false);
                if($now->month == $firstDate->month && $now->year == $firstDate->year) {
                    $data['pembagi'] = $now->day;
                }
                else {
                    $data['pembagi'] = $end_date->day;
                }
    
                $projectId = session()->get('current_project')->id;
    
                $stock_ins = Warehouse::join('parties', 'parties.id', 'warehouses.branch_id')
                ->join('stock_entries', 'stock_entries.warehouse_id', 'warehouses.id')
                ->join('projects', 'projects.id', 'stock_entries.project_id')
                ->join('stock_entry_details', 'stock_entry_details.stock_entry_id', 'stock_entries.id')
                ->join('storages', 'storages.id', 'stock_entry_details.storage_id')
                ->join('items', 'items.id', 'stock_entry_details.item_id')
                ->join('uoms', 'uoms.id', 'items.default_uom_id')
                ->join('commodities', 'commodities.id', 'items.commodity_id')
                ->where('warehouses.is_active', 1)
                ->where('stock_entries.project_id',  $projectId)
                ->where(DB::raw('Date(stock_entry_details.updated_at)'),'<=', $end_date)
                ->where('stock_entries.status', 'Completed')
                ->where('stock_entries.type', 'inbound');
    
                if($projectId == '337') {
                    $stock_ins->select([
                        'parties.name as branch_name',
                        'warehouses.percentage_buffer',
                        'warehouses.name as warehouse_name',
                        'warehouses.id as warehouse_id',
                        'projects.name as project_name',
                        'items.sku',
                        'items.weight',
                        'warehouses.total_weight as capacity',
                        'items.name as name_sku',
                        'uoms.name as uom_name',
                        'commodities.name as commodity_name',
                        'items.id as item_id',
                        'warehouses.ownership as status_gudang',
                        'stock_entries.project_id',
                        'storages.damage',
                        DB::raw("sum(stock_entry_details.qty) as total_in, CONCAT(stock_entry_details.item_id,'_',warehouses.id,'_', stock_entries.project_id,'_', storages.damage) AS concat_id")
                    ]);
                } else {
                    $stock_ins->select([
                        'parties.name as branch_name',
                        'warehouses.percentage_buffer',
                        'warehouses.name as warehouse_name',
                        'warehouses.id as warehouse_id',
                        'projects.name as project_name',
                        'commodities.name as commodity_name',
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

                if(Auth::user()->hasRole('WarehouseManager')) {
                    $warehouses = Warehouse::where('branch_id', Auth::user()->branch_id)->where('is_active', 1)->pluck('id');
                    // dd($warehouses);
                    $stock_ins
                        ->whereIn('stock_entries.warehouse_id', $warehouses);
                } else {
                    $stock_ins
                    ->where('stock_entries.warehouse_id', session()->get('warehouse_id'));
                }
                $stock_ins = $stock_ins->groupBy('concat_id')
                ->get();
                // dd($stock_ins);
                $a = [];
    
                $stock_out_after_begining = StockEntryDetail::join('stock_entries', 'stock_entries.id', 'stock_entry_details.stock_entry_id');
                if($projectId == '337') {
                    $stock_out_after_begining->select([
                        DB::raw("sum(stock_entry_details.qty) as total_out, CONCAT(day(stock_entry_details.updated_at),'_',stock_entry_details.warehouse_id,'_',stock_entry_details.item_id,'_', stock_entries.project_id,'_', storages.damage) AS m_concat_id, day(stock_entry_details.updated_at) as date_out"), 'stock_entry_details.item_id'
                        , 'stock_entry_details.warehouse_id', 'stock_entries.project_id', 'storages.damage','stock_entry_details.storage_id'
                    ]);
                }else {
                    $stock_out_after_begining->select([
                        DB::raw("sum(stock_entry_details.qty) as total_out, CONCAT(stock_entry_details.warehouse_id,'_',stock_entry_details.item_id,'_', stock_entries.project_id) AS m_concat_id, day(stock_entry_details.updated_at) as date_out"), 'stock_entry_details.item_id'
                        , 'stock_entry_details.warehouse_id', 'stock_entries.project_id', 'storages.damage'
                    ]);
                }
    
                $stock_out_after_begining->where('stock_entries.status', 'Completed')
                ->join('storages', 'storages.id', 'stock_entry_details.storage_id')
                ->where('stock_entries.type', 'outbound')
                ->where('stock_entries.project_id',$projectId);
                if($request->warehouse_id != 'all') {
                    $stock_out_after_begining
                    ->where('stock_entries.warehouse_id', session()->get('warehouse_id'));
                }
                if(Auth::user()->hasRole('WarehouseManager')) {
                    $stock_out_after_begining
                        ->whereIn('stock_entries.warehouse_id', $warehouses);
                }
                $stock_out_after_begining = $stock_out_after_begining
                ->whereBetween(DB::raw('Date(stock_entry_details.updated_at)'), [$firstDate, $end_date])
                ->groupBy('m_concat_id')->get();
    
                $arr_out_after_begining = [];
                foreach ($stock_out_after_begining as $out_after) {
                    if($projectId == '337') {
                        $arr_out_after_begining[$out_after->item_id."_".$out_after->warehouse_id."_".$out_after->project_id."_".$out_after->damage][$out_after->date_out]=$out_after->total_out;
                    }else{
                        $arr_out_after_begining[$out_after->item_id."_".$out_after->warehouse_id."_".$out_after->project_id][$out_after->date_out]=$out_after->total_out;
                    }
                }
        
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
                ->where(DB::raw('Date(stock_entry_details.updated_at)'),'<=', $end_date);
                
                if(Auth::user()->hasRole('WarehouseManager')) {
                    $stock_outs
                        ->whereIn('stock_entries.warehouse_id', $warehouses);
                } else {
                   
                    $stock_outs
                    ->where('stock_entries.warehouse_id', session()->get('warehouse_id'));
                    
                }
                $stock_outs = $stock_outs->groupBy('m_concat_id')->get();
    
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
                
                if(Auth::user()->hasRole('WarehouseManager')) {
                    $stock_outs
                        ->whereIn('stock_entries.warehouse_id', $warehouses);
                } else {
                    $stock_outs
                        ->where('stock_entries.warehouse_id', session()->get('warehouse_id'));
                }
                $stock_in_after_begining = $stock_in_after_begining->where('stock_entries.status', 'Completed')
                ->where('stock_entries.type', 'inbound')
                ->where('stock_advance_notices.advance_notice_activity_id', '<>',20)
                ->where('stock_entries.project_id',  $projectId)
                ->whereBetween(DB::raw('Date(stock_entry_details.updated_at)'),[$firstDate, $end_date])
                ->groupBy('m_concat_id')->get();
    
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
                            $qty_out_after = array_sum($arr_out_after_begining[$in->item_id."_".$in->warehouse_id."_".$in->project_id."_".$in->damage]);
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
                            $qty_out_after = array_sum($arr_out_after_begining[$in->item_id."_".$in->warehouse_id."_".$in->project_id]);
                        }else{
                            $qty_out_after = 0;
                        }
                    }
                    $total_out = $qty_out - $qty_out_after;
    
                    $begining = ($in->total_in - $qty_in_after) - (($qty_out)- $qty_out_after);
                    
                    $stock = $in->total_in - $qty_out;
                   
                    $after_begining_in = $qty_in_after;
                    $after_begining_out = $qty_out_after;
                    $utilitas = round((($stock/$in->capacity)*100),2);
                    $kondisi = $in->damage == 1 ? ' (DAMAGE)' : '';
                    if($projectId == '337') {
                        $a[] = [
                        'branch' => $in->branch_name,
                        'percentage_buffer' => $in->percentage_buffer,
                        'warehouse' => $in->warehouse_name,
                        'project'   => $in->project_name,
                        'sku'    =>$in->sku,
                        'sku_name' => $in->name_sku . ' '. $kondisi,
                        'uom_name' => $in->uom_name,
                        'commodity_name' => $in->commodity_name,
                        'begining' => $begining,
                        'after_begining_in' => $after_begining_in,
                        'after_begining_out' => $after_begining_out,
                        'stock' => $stock,
                        'item_id' => $in->item_id,
                        'warehouse_id' => $in->warehouse_id,
                        'project_id' => $in->project_id,
                        'damage' => $in->damage,
                        ];
                    } else {
                        $a[] = [
                        'branch' => $in->branch_name,
                        'percentage_buffer' => $in->percentage_buffer,
                        'warehouse' => $in->warehouse_name,
                        'project'   => $in->project_name,
                        'sku'    =>$in->sku,
                        'sku_name' => $in->name_sku . ' '. $kondisi,
                        'commodity_name' => $in->commodity_name,
                        'item_id' => $in->item_id,
                        'warehouse_id' => $in->warehouse_id,
                        'project_id' => $in->project_id,
                        'uom_name' => $in->uom_name,
                        'begining' => $begining,
                        'after_begining_in' => $after_begining_in,
                        'after_begining_out' => $after_begining_out,
                        'stock' => $stock,
                        'damage' => 0,
                        ]; 
                    }
                }
                // dd($arr_out_after_begining['3848_892_337_0'][12]);
                // $data['out'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']][$i]
                $data['outs'] = $arr_out_after_begining;
                $data['report'] = $a;
            }

        }
        
        return view('report.mutation_month')->with(['data'=> $data]);
    }
}
