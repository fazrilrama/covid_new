<?php

namespace App\Http\Controllers;

use App\AdvanceNotice;
use App\AdvanceNoticeDetail;
use App\AdvanceNoticeActivity;
use App\StockTransportDetail;
use App\WarehouseOfficer;
use App\City;
use App\Company;
use App\TransportType;
use App\Party;
use App\Warehouse;
use App\Storage;
use App\Project;
use App\PartyType;
use App\DataLog;
use Auth;
use Carbon;
use App\Spk;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Jobs\InboundOutbondJob;
use App\StockTransport;
use App\Contract;
use App\ContractWarehouse;
use PDF;

class StockTransferOrderController extends Controller
{
    protected $user;
    
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
    }



    public function index($type = 'inbound', AdvanceNotice $advanceNotice)
    {
        $message = NULL;
        $collections = null;
        
        // 1. Kalau ada data cabang, filter datanya
        if(Auth::user()->branch && !Auth::user()->hasRole('CargoOwner')) {
            // 1. Kalau inbound, keluarkan data dimana consignee equals to user branch data
            
            $collections = AdvanceNotice::where('type',$type)
                    ->where('code', 'LIKE', '%STO%')
                    ->where('project_id', session()->get('current_project')->id)  
                    ->where('status','Completed')
                    ->orderBy('id', 'desc')
                    ->get();

            if($type == 'inbound') {
                $collections = $collections->filter(function ($item) {
                    return $item->consignee_id == Auth::user()->branch->id;
                });
            } else {
                // 2. Kalau outbound, keluarkan data dimana shipper equals to user branch data
                $collections = $collections->filter(function ($item) {
                    return $item->shipper_id == Auth::user()->branch->id;
                });
            }

            // 2. Kalau yg login WH supervisor, di filter lg datanya berdasarkan yang di assign ke dia saja
            if(Auth::user()->hasRole('WarehouseSupervisor')) {
                $collections = $collections->filter(function ($item) {
                    $item->employee_name = strtolower($item->employee_name);
                    if(Auth::user()->last_name) {
                        return $item->employee_name == strtolower(Auth::user()->first_name).' '.strtolower(Auth::user()->last_name);
                    } else {
                        return $item->employee_name == strtolower(Auth::user()->first_name);
                    }
                });
            }
        }

        if(Auth::user()->hasRole('CargoOwner')) {
                $collections = AdvanceNotice::where('type',$type)
                        ->where('code', 'LIKE', '%STO%')
                        ->where('project_id', session()->get('current_project')->id)  
                        ->orderBy('id', 'desc')
                        ->get();
            }

        
        
        // Get outstanding items ini masih salah
        foreach ($collections as $collection) {
            $total_items = $collection->details->sum('qty');
            $delivered_items = 0;
            $transports = $collection->transports()->ofStatus('Completed')->get();
            foreach ($transports as $transport) {
                $delivered_items += $transport->details->sum('qty');
            }
            $collection->outstanding = $total_items - $delivered_items;
        }
        return view('stock_transfer_order.index',compact('collections','type', 'advanceNotice','warehouseOfficers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($type = 'inbound')
    {
        $action = route('stock_transfer_order.store');
        $method = 'POST';
        $cities = City::pluck('name', 'id');
        $projectId = session()->get('current_project')->id;
        $contracts = Contract::where('project_id', $projectId)
            ->get(['id','number_contract']);
        $transport_types = TransportType::pluck('name', 'id');
        if($type == 'inbound') {
            $shippers = Party::whereHas('party_types', function ($query) {
                $query->where('name','shipper');
            })->get();
            $consignees = Party::whereHas('party_types', function ($query) {
                $query->where('name','branch');
            })->get();
        } else {
            $shippers = Party::whereHas('party_types', function ($query) {
                $query->where('name','branch');
            })->get();
            $consignees = Party::whereHas('party_types', function ($query) {
                $query->where('name','consignee');
            })->get();
        }
        $activities = AdvanceNoticeActivity::pluck('name', 'id');
        $advanceNotice = new AdvanceNotice;
        
        return view('stock_transfer_order.create',compact('contracts', 'action','method','advanceNotice','transport_types','cities','shippers','consignees','activities','type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'contract_number' => 'required',
            'spmp_number' => 'required',
            'etd' => 'required|date',
            'eta' => 'required|date',
            'shipper_address' => 'required',
            'consignee_address' => 'required',
            // 'sptb_num' => 'required',
            'sppk_num' => 'required',
            'sppk_doc' => 'required',
            'type' => 'in:inbound,outbound',
            // 'annotation' => 'required',
            // 'contractor' => 'required',
            // 'head_ds' => 'required',
            'disclaimer' => 'accepted'
        ]);

        $model = new AdvanceNotice;
        $model->type = $request->get('type');
        $model->advance_notice_activity_id = $request->get('advance_notice_activity_id');
        $model->transport_type_id = $request->get('transport_type_id');
        /**
         * NEW
         */
        //$model->sptb_num = $request->get('sptb_num');
        $model->sptb_num = null;
        $model->sppk_num = $request->get('sppk_num');
        $model->sppk_doc = $request->sppk_doc->store('doc', 'public');

        $model->etd = $request->get('etd');
        $model->eta = $request->get('eta');
        $model->origin_id = $request->get('origin_id');
        $model->destination_id = $request->get('destination_id');
        $model->ref_code = $request->get('ref_code');
        $model->shipper_id = $request->get('shipper_id');
        $model->shipper_address = $request->get('shipper_address');
        $model->consignee_id = $request->get('consignee_id');
        $model->consignee_address = $request->get('consignee_address');
        $model->contract_number = Contract::find($request->get('contract_number'))->number_contract;
        $model->spmp_number = $request->get('spmp_number');
        $model->user_id = Auth::user()->id;
        $model->project_id = session()->get('current_project')->id;
        $model->is_sto = 1;

        $model->warehouse_id = null;

        /**
         * NEW BULOG
         */
        $model->annotation              = $request->get('annotation');
        $model->contractor              = $request->get('contractor');
        $model->head_ds                 = $request->get('head_ds');
        $model->save();

        
        //Generate doc code
        $user_company_id = sprintf("%04d", session()->get('current_project')->id);
        $model_code = $model->getDocStoCode($request->get('type'));
        $year_month = Carbon::now()->format('ym');
        $doc_id = sprintf("%04d", $model->id);
        $doc_code = $user_company_id.'.'.$model_code.'.'.$year_month.'.'.$doc_id;
        $model->code = $doc_code;
        $model->save();

        if($model->type == 'inbound'){
            $sub_type = 'ain';
        }
        else{
            $sub_type = 'aon';
        }

        //create data log
        // $data_log = array(
        //     'user_id' => $model->user_id,
        //     'type' => $model->type,
        //     'sub_type' => $sub_type,
        //     'record_id' => $model->id,
        //     'status' => 'start',
        // );

        // $input = array_except($data_log, '_token');
        // $DataLog = DataLog::create($input);

        try {
            activity()
                ->performedOn($model)
                ->causedBy(Auth::user())
                ->withProperties($model)
                ->log('Planning ' . ($request->get('type') == 'inbound' ? 'AIN' : 'AON') );
        } catch (\Exception $e) {
            
        }
        return redirect('stock_transfer_order/'.$model->id.'/edit')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AdvanceNotice  $advanceNotice
     * @return \Illuminate\Http\Response
     */
    public function show(AdvanceNotice $advanceNotice)
    {
        //warehouse yg akan keluar nanti berdasar divre penerima
        if($advanceNotice->type == 'inbound'){
            $party = Party::find($advanceNotice->consignee_id);
        }
        //warehouse yg akan keluar nanti berdasar divre pengirim
        else{
            $party = Party::find($advanceNotice->shipper_id);
        }

        //QUESTION
        if($party) {
            //warehouse akan keluar jika warehouse tersebut mempunyai storage
            $warehouses = Storage::join('warehouses as w', 'w.id', '=', 'storages.warehouse_id')
                ->where('w.branch_id', $party->id)
                ->where('w.region_id', $party->region_id)
                ->groupBy('w.id')
                ->get([
                    'w.id',
                    'w.name',
                    'w.code',
                ]);
        } else {
            //warehouse akan keluar jika warehouse tersebut mempunyai storage
            $warehouses = Storage::join('warehouses as w', 'w.id', '=', 'storages.warehouse_id')
                ->groupBy('w.id')
                ->get([
                    'w.id',
                    'w.name',
                    'w.code',
                ]);
        }

        return view('stock_transfer_order.view', compact('advanceNotice','warehouses'));
    }

    public function print_sptb(AdvanceNotice $advanceNotice)
    {
        return view('stock_transfer_order.print_sptb', compact('advanceNotice'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AdvanceNotice  $advanceNotice
     * @return \Illuminate\Http\Response
     */
    public function edit(AdvanceNotice $advanceNotice)
    {
        // Check if owner
        if(Auth::user()->id == $advanceNotice->user_id) {
            $action = route('stock_transfer_order.update', $advanceNotice->id);
            $method = 'PUT';
            $cities = City::pluck('name', 'id');
            $transport_types = TransportType::pluck('name', 'id');
            $projectId = session()->get('current_project')->id;

            $total_qty_items = $advanceNotice->details->sum('qty');

            $contracts = Contract::where('project_id', $projectId)
                ->get(['id','number_contract']);
            if($advanceNotice->type == 'inbound') {
                $shippers = Party::whereHas('party_types', function ($query) {
                    $query->where('name','shipper');
                })->get();
                $consignees = Party::whereHas('party_types', function ($query) {
                    $query->where('name','branch');
                })->get();
            } else {
                $shippers = Party::whereHas('party_types', function ($query) {
                    $query->where('name','branch');
                })->get();
                $consignees = Party::whereHas('party_types', function ($query) {
                    $query->where('name','consignee');
                })->get();
            }
            $activities = AdvanceNoticeActivity::pluck('name', 'id');
            $type = $advanceNotice->type;
            return view('stock_transfer_order.edit',compact('contracts', 'action','method','advanceNotice','transport_types','cities','shippers','consignees','activities','type','total_qty_items'));
        } else {
            return redirect()->back()->with('error','Tidak mempunyai akses');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AdvanceNotice  $advanceNotice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AdvanceNotice $advanceNotice)
    {
        // Check if owner
        if(Auth::user()->id == $advanceNotice->user_id) {
            //Validate
            $request->validate([
                'contract_number' => 'required',
                'spmp_number' => 'required',
                'sptb_num' => 'required',
                'sppk_num' => 'required',
                'sppk_doc' => 'required',
                'etd' => 'required|date',
                'eta' => 'required|date',
                'shipper_address' => 'required',
                'consignee_address' => 'required',
                'type' => 'in:inbound,outbound',
                'annotation' => 'required',
                'contractor' => 'required',
                'head_ds' => 'required',
                'disclaimer' => 'accepted'
            ]);

            // Note ketika edit, field type, user, code tidak perlu di update
            $model = $advanceNotice;
            $model->advance_notice_activity_id = $request->get('advance_notice_activity_id');
            $model->transport_type_id = $request->get('transport_type_id');
            $model->etd = $request->get('etd');
            $model->eta = $request->get('eta');
            $model->origin_id = $request->get('origin_id');
            $model->destination_id = $request->get('destination_id');
            $model->ref_code = $request->get('ref_code');
            $model->shipper_id = $request->get('shipper_id');
            $model->shipper_address = $request->get('shipper_address');
            // if ($request->get('type') == 'inbound') {
            $model->consignee_id = $request->get('consignee_id');
            $model->consignee_address = $request->get('consignee_address');
            // }
            /**
             * NEW BULOG
             */
            // $model->annotation              = $request->get('annotation');
            // $model->contractor              = $request->get('contractor');
            // $model->head_ds                 = $request->get('head_ds');
            $model->save();

            try {
                activity()
                    ->performedOn($model)
                    ->causedBy(Auth::user())
                    ->withProperties($model)
                    ->log('Update ' . ($advanceNotice->type == 'inbound' ? 'AIN' : 'AON') );
            } catch (\Exception $e) {
                
            }
            
            return redirect('stock_transfer_order/'.$model->id.'/edit')->with('success', 'Data berhasil disimpan');
        } else {
            return redirect()->back()->with('error','Tidak mempunyai akses');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AdvanceNotice  $advanceNotice
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdvanceNotice $advanceNotice)
    {
        // Check if owner
        if(Auth::user()->id == $advanceNotice->user_id) {
            $type = $advanceNotice->type;
            $advanceNotice->details()->delete();
            $advanceNotice->delete();

            try {
                activity()
                    ->performedOn($advanceNotice)
                    ->causedBy(Auth::user())
                    ->withProperties($advanceNotice)
                    ->log('Delete ' . ($type == 'inbound' ? 'AIN' : 'AON') );
            } catch (\Exception $e) {
                
            }

            return redirect('stock_transfer_order/'.$type)->with('success','Data berhasil dihapus');
        } else {
            return redirect()->back()->with('error','Tidak mempunyai akses');
        }
    }

    // public function closed(AdvanceNotice $advanceNotice, Request $request) {
    //     dd($request->all());
    //     $request->validate([
    //         'password' => 'required|string',
    //     ]);

    //     $hasher = app('hash');
    //     if (!$hasher->check($request->input('password'), Auth::user()->password)) {
    //         return redirect()->back()->with('error','Password salah');
    //     }
    //     $advanceNotice->status = 'Closed';
    //     $advanceNotice->save();
    //     return redirect()->back()->with('success','AIN berhasil di Closed');
    // }
    
    public function completed(AdvanceNotice $advanceNotice, Request $request) {
        $request->validate([
            'password' => 'required|string',
        ]);

        $hasher = app('hash');
        if (!$hasher->check($request->input('password'), Auth::user()->password)) {
            return redirect()->back()->with('error','Password salah');
        }
        $advanceNotice->status = 'Completed';
        $advanceNotice->save();
        try {
            activity()
                ->performedOn($advanceNotice)
                ->causedBy(Auth::user())
                ->withProperties($advanceNotice)
                ->log('Completed ' . ($advanceNotice->type == 'inbound' ? 'AIN' : 'AON') );
        } catch (\Exception $e) {
            
        }

        //create data log
        

        if($advanceNotice->type == 'inbound'){
            $sub_type = 'ain';
        }
        else{
            $sub_type = 'aon';
        }

        //create data log
        // $data_log = array(
        //     'user_id' => Auth::user()->id,
        //     'type' => $advanceNotice->type,
        //     'sub_type' => $sub_type,
        //     'record_id' => $advanceNotice->id,
        //     'status' => 'completed',
        // );

        // $input = array_except($data_log, '_token');
        // $DataLog = DataLog::create($input);

        return redirect()->back()->with('success','Successfully completed');
    }
    
    public function closed(AdvanceNotice $advanceNotice, Request $request) {
        $request->validate([
            'password' => 'required|string',
        ]);

        $hasher = app('hash');
        if (!$hasher->check($request->input('password'), Auth::user()->password)) {
            return redirect()->back()->with('error','Password salah');
        }
        $advanceNotice->status = 'Closed';
        $advanceNotice->save();
        try {
            activity()
                ->performedOn($advanceNotice)
                ->causedBy(Auth::user())
                ->withProperties($advanceNotice)
                ->log('Closed ' . ($advanceNotice->type == 'inbound' ? 'AIN' : 'AON') );
        } catch (\Exception $e) {
            
        }
        return redirect()->back()->with('success','Successfully closed');
    }

    public function getJson($id) {
        $advanceNotice = AdvanceNotice::findOrFail($id);

        if($advanceNotice->transport_type){
            $advanceNotice->transport_type_name = $advanceNotice->transport_type->name;
        }
        else{
            $advanceNotice->transport_type_name = null;
        }

        if($advanceNotice->warehouse){
            $advanceNotice->warehouse_name = $advanceNotice->warehouse->name;        
        }
        else{
            $advanceNotice->warehouse_name = null;        
        }

        $advanceNotice->warehouseOfficers = Warehouse::join('warehouse_officer as wo', 'wo.warehouse_id', '=', 'warehouses.id')->join('users as u', 'u.id', '=', 'wo.user_id')->join('role_user as ru','ru.user_id', '=', 'u.id')->join('roles as r','r.id','=', 'ru.role_id')->where('r.id','=', 5)->where('warehouses.id','=', $advanceNotice->warehouse->id)->get(['u.first_name']);
        
        
        return response()->json($advanceNotice);
    }

    public function getDataSpk($contract_id) {
        $spks = Contract::find($contract_id)->spks;
        return $spks;
    }

    public function assignTo(AdvanceNotice $advanceNotice, Request $request) {
        // copy STOO
        $newAn = $advanceNotice->replicate();
        $newAn->type = ($advanceNotice->type == 'inbound' ? 'outbound' : 'inbound');
        $newAn->is_sto = 1;
        $newAn->save();

        // genereate STOI
        $user_company_id = sprintf("%04d", session()->get('current_project')->id);
        $model_code = $advanceNotice->getDocStoCode('inbound');
        $year_month = Carbon::now()->format('ym');
        $doc_id = sprintf("%04d", $newAn->id);
        $doc_code = $user_company_id.'.'.$model_code.'.'.$year_month.'.'.$doc_id;
        $newAn->code = $doc_code;
        $newAn->save();

        // create STOI Details
        $newAnDetails = new AdvanceNoticeDetail;
        foreach($advanceNotice->details as $newRow) {
            $newAnDetails = $newRow->replicate();
            $newAnDetails->stock_advance_notice_id = $newAn->id;
            $newAnDetails->save();
        }
        /**
         * assign to user
         */

        $advanceNotice->warehouse_id = $request->get('warehouse_id');
        $advanceNotice->employee_name = $request->get('employee_name');
        $advanceNotice->save();
        $wh = Warehouse::find($request->get('warehouse_id'));
        $u = User::find($request->get('employee_id'));

        InboundOutbondJob::dispatch($advanceNotice, $u, auth()->user())->delay(now()->addSeconds(3));

        return redirect()->back()->with('success','Data berhasil disimpan');
    }

    public function get_warehouse_officer(Request $request){
        $warehouse_id = $request->input('warehouse_id'); 

        if (isset($warehouse_id)) {
            
            $warehouse = Warehouse::find($warehouse_id);
            $warehouse_officer = WarehouseOfficer::where('warehouse_id' ,$warehouse->id)->get();

            foreach ($warehouse_officer as $key => $item) {
                // kalau petugas gudang bukan supervisor, jangan ditampilkan
                if($item->user->hasRole('WarehouseOfficer')) {$warehouse_officer->forget($key);continue;}
            }
            
            return view('stock_transfer_order.select_warehouse_officer', compact('warehouse','warehouse_officer'));
        }       
    }

    public function to_storage_list($type){
        //satu user hanya bisa di satu gudang (logicnya blm bisa dibikin)
        $warehouseId = WarehouseOfficer::join('users', 'warehouse_officer.user_id','=','users.id')
                        ->where('users.id', Auth::user()->id)->pluck('warehouse_officer.warehouse_id')->first();

        $projectId = session()->get('current_project')->id;

        $storages = Storage::join('storage_projects', 'storages.id', '=', 'storage_projects.storage_id')
                        ->where('storage_projects.project_id', $projectId)
                        ->where('storages.warehouse_id', $warehouseId)
                        //->where('storages.status', 0)
                        ->get(['storages.code', 'storages.id','storages.warehouse_id','storage_projects.project_id','storages.status']);

        return view('stock_transfer_order.storage_list',compact('storages','type'));

    }

    public function change_storage_status(Request $request)
    {
        $storage = Storage::find($request->input('storage_id'));
        $status = $request->input('status');
        $status_name = '';
        if($status == 1){
            $status_name = 'buka';
        }
        else{
            $status_name = 'tutup';
        }
        $type = $request->input('type');

        $storage->status = $status;
        $storage->save();

        return redirect()->route('to_storage_list', $type)->with('success', 'Storage berhasil di'.$status_name);
    }

    public function toggleIsArrived(Request $request, $aon_id)
    {
        $advanceNotice = AdvanceNotice::find($aon_id);
        $advanceNotice->is_arrived = !$advanceNotice->is_arrived;
        $advanceNotice->save();

        $status = 'sudah tiba';

        if($advanceNotice->is_arrived == false) {
            $status = 'belum tiba';
        }

        return redirect()->route('stock_transfer_order.index',[$advanceNotice->type, $advanceNotice])->with('success','Berhasil ubah status menjadi ' . $status);            
    }

}
