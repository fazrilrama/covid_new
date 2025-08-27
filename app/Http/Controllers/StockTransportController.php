<?php

namespace App\Http\Controllers;

use App\StockTransport;
use App\StockTransportDetail;
use App\AdvanceNoticeDetail;
use App\AdvanceNotice;
use App\City;
use App\User;
use App\TransportType;
use App\Party;
use App\PartyType;
use App\Storage;
use App\Warehouse;
use App\DataLog;
use App\RecFom;
use App\StockOpname;
use Auth;
use Carbon;
use DB;
use Response;
use App\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use App\Mail\InboundGoodReceivedPlan;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StockTransportDetailExport;

use App\Rules\GrDpCompleteRule;

class StockTransportController extends Controller
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
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type = 'inbound', Request $request)
    {
        /*$collections = StockTransport::where('type',$type)
                        ->where('project_id', session()->get('current_project')->id)
                        ->orderBy('id', 'desc')
                        ->get();

        // 1. Kalau ada data cabang, filter datanya
        if(Auth::user()->branch && !Auth::user()->hasRole('CargoOwner')) {
            // 1. Kalau inbound, keluarkan data dimana consignee equals to user branch data
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
                    
                    $item->advance_notice->employee_name = strtolower($item->advance_notice->employee_name);
                    if(Auth::user()->last_name) {
                        return $item->advance_notice->employee_name == strtolower(Auth::user()->first_name).' '.strtolower(Auth::user()->last_name);
                    } else {
                        return $item->advance_notice->employee_name == strtolower(Auth::user()->first_name);
                    }
                });
            }
        }*/
	$collections = StockTransport::where('stock_transports.type',$type)
                        ->select([
                            'stock_transports.id',
                            'stock_transports.code as code', 
                            'stock_advance_notices.code as advance_code',
                            'origin.name as origin',
                            'destination.name as destination',
                            'stock_transports.etd',
                            'stock_transports.eta',
                            'shipper.name as shipper_name',
                            'consignee.name as consignee_name',
                            'stock_transports.status',
                            'stock_transports.user_id'
                        ])
                        ->join('stock_advance_notices', 'stock_advance_notices.id', 'stock_transports.advance_notice_id')
                        ->join('cities as origin', 'origin.id', 'stock_transports.origin_id')
                        ->join('cities as destination', 'destination.id', 'stock_transports.destination_id')
                        ->join('parties as shipper', 'shipper.id', 'stock_transports.shipper_id')
                        ->join('parties as consignee', 'consignee.id', 'stock_transports.consignee_id')
                        ->where('stock_transports.project_id', session()->get('current_project')->id)
                        ->where('advance_notice_activity_id','<>', 20)
                        ->orderBy('stock_transports.id', 'desc');

            if(Auth::user()->branch && !Auth::user()->hasRole('CargoOwner')) {
            // 1. Kalau inbound, keluarkan data dimana consignee equals to user branch data
            if($type == 'inbound') {
                // $collections = $collections->filter(function ($item) {
                //     return $item->consignee_id == Auth::user()->branch->id;
                // });
                $collections
                ->where('stock_transports.consignee_id', Auth::user()->branch->id);
            } else {
                // 2. Kalau outbound, keluarkan data dimana shipper equals to user branch data
                // $collections = $collections->filter(function ($item) {
                //     return $item->shipper_id == Auth::user()->branch->id;
                // });
                $collections->where('stock_transports.shipper_id', Auth::user()->branch->id);
            }

            // $collections = $collections->get();
            // 2. Kalau yg login WH supervisor, di filter lg datanya berdasarkan yang di assign ke dia saja
            if(Auth::user()->hasRole('WarehouseSupervisor')) {

                // $collections = $collections->filter(function ($item) {
                    if(Auth::user()->last_name){
                        $collections
                                ->where('stock_advance_notices.employee_name', strtolower(Auth::user()->first_name).' '.strtolower(Auth::user()->last_name));
                    } else {
                        $collections
                                ->where('stock_advance_notices.employee_name', strtolower(Auth::user()->first_name));
                    }
                    // $item->advance_notice->employee_name = strtolower($item->advance_notice->employee_name);
                    // if(Auth::user()->last_name) {
                    //     return $item->advance_notice->employee_name == strtolower(Auth::user()->first_name).' '.strtolower(Auth::user()->last_name);
                    // } else {
                    //     return $item->advance_notice->employee_name == strtolower(Auth::user()->first_name);
                    // }

                // });

            }
        }


        //return $collections;
        $collections = $collections->limit(1000)->get();
        // dd(count($collections));
        //return $collections;
        if($request->get('submit')) {
            // dd('masuk');
            return Excel::download(new StockTransportDetailExport, 'List Inbound-'.Carbon::now()->format('d-m-y').'.xlsx');

        } else {
            return view('stock_transports.index')->with(['collections' => $collections,'type' => $type]);
        }
        //return view('stock_transports.index',compact('collections','type', 'advanceNotices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($type = 'inbound')
    {
        $stock = StockOpname::where('warehouse_id', session()->get('warehouse_id'))->where('status', 'Processed')->first();
        if($stock) {
            return redirect()->back()->with('error','Ada Stock Opname yang belum di Complete!');
        }
        $status="create";
        $action = route('stock_transports.store');
        $method = 'POST';
        $projectId = session()->get('current_project')->id;

        $collections = StockTransport::where('type',$type)
                        ->where('project_id', session()->get('current_project')->id)
                        ->orderBy('id', 'desc')
                        ->get(['advance_notice_id']);

        //$filterAdvanceNotice = [];
            
        $cities = City::pluck('name','id');
        
        if(Auth::user()->branch) {
            $advance_notices = AdvanceNotice::where('type',$type)
                        // ->whereNotIn('stock_advance_notices.id', $filterAdvanceNotice)
                        ->where('project_id', session()->get('current_project')->id)
                        ->where('status', 'Completed')
                        ->where('employee_name', Auth::user()->first_name.' '.Auth::user()->last_name)
                        ->where('advance_notice_activity_id','<>', 20)
                        ->orderBy('id', 'desc')
                        ->get(['code','id','is_arrived', 'type']);
        } else {
            $advance_notices = AdvanceNotice::where('type',$type)
                        // ->whereNotIn('stock_advance_notices.id', $filterAdvanceNotice)
                        ->where('project_id', session()->get('current_project')->id)
                        ->where('advance_notice_activity_id','<>', 20)
                        ->where('status', 'Completed')
                        ->orderBy('id', 'desc')
                        ->get(['code','id','is_arrived', 'type']);
        }
        
        //check apakah outstanding ain/aon sudah habis atau belum
        foreach ($advance_notices as $advance_notice) {
            $sumBefore = AdvanceNoticeDetail::where('stock_advance_notice_id', $advance_notice->id)
                ->sum('qty');

            if($type == 'inbound'){
                $sumComplete = StockTransportDetail::join('stock_transports as st', 'st.id', '=', 'stock_transport_details.stock_transport_id')
                ->where('st.advance_notice_id', $advance_notice->id)
                ->where('st.status', 'Completed')
                // ->whereNull('st.deleted_at')
                ->sum('stock_transport_details.qty');

                $sumProcessed = StockTransportDetail::join('stock_transports as st', 'st.id', '=', 'stock_transport_details.stock_transport_id')
                ->where('st.advance_notice_id', $advance_notice->id)
                ->where('st.status', 'Processed')
                // ->whereNull('st.deleted_at')
                ->sum('stock_transport_details.plan_qty');

                $sumAfter = $sumComplete + $sumProcessed;
            }
            else{
                $sumAfter = StockTransportDetail::join('stock_transports as st', 'st.id', '=', 'stock_transport_details.stock_transport_id')
                ->where('st.advance_notice_id', $advance_notice->id)
                // ->whereNull('st.deleted_at')
                ->sum('stock_transport_details.plan_qty');
            }
            
            $advance_notice->show = ($sumBefore - $sumAfter) > 0;
        }
        
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
                $query->where('name','shipper');
            })->get();
        }

        $parties = Party::select(['id', 'name'])->get();

        $stockTransport = new StockTransport;
        return view('stock_transports.create',compact('action','method','stockTransport','transport_types','advance_notices','cities','shippers','consignees','type','status', 'parties'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (empty($request->warehouse_id)) {
            return redirect()->back()->withErrors('Gudang kosong. Silahkan buat Gudang terlebih dahulu');
        }
        $stock = StockOpname::where('warehouse_id', session()->get('warehouse_id'))->where('status', 'Processed')->first();
        if($stock) {
            return redirect()->back()->with('error','Ada Stock Opname yang belum di Complete!');
        }

        if($request->has('send_foms')) {
            $validations = [
                'advance_notice_id' => 'required|exists:stock_advance_notices,id',
                'warehouse_id'      => 'required|exists:warehouses,id',
                'etd'               => 'required|date',
                'eta'               => 'required|date',
                'type'              => 'in:inbound,outbound',
                'origin_address'    => 'required',
                'origin_postcode'   => 'required',
                'origin_latitude'   => 'required',
                'origin_longitude'  => 'required',
                'dest_address'      => 'required',
                'dest_postcode'     => 'required',
                'dest_latitude'     => 'required',
                'dest_longitude'    => 'required',
            ];
        } else {
            $validations = [
                'advance_notice_id' => 'required|exists:stock_advance_notices,id',
                'warehouse_id'      => 'required|exists:warehouses,id',
                'etd'               => 'required|date',
                'eta'               => 'required|date',
                'type'              => 'in:inbound,outbound',
                /**
                 * Additional Validate for GR
                 */
                // 'do_number'         => 'required_if:type,inbound',
                // 'driver_name'       => 'required_if:type,inbound',
                // 'wp_number'         => 'required_if:type,inbound',
                'driver_phone'      => 'nullable|alpha_num|min:7',
                'police_number'     => 'nullable|alpha_num|max:9',
                // 'lhpk_number'       => 'required_if:type,inbound',
                // 'lhpk_issue_date'   => 'required_if:type,inbound',
                // 'fleet_arrived'     => 'required_if:type,inbound',
                // 'unloading_start'   => 'required_if:type,inbound',
                // 'unloading_end'     => 'required_if:type,inbound',
            ];
        }
        $request->validate($validations);

        // $request->merge(['ain_aon_id' => $request->get('advance_notice_id')]);

        // $request->validate([
        //     'ain_aon_id' => [new GrDpCompleteRule()]
        // ]);


        DB::beginTransaction();
        try {
            $model                      = new StockTransport;
            $model->type                = $request->get('type');
            $model->advance_notice_id   = $request->get('advance_notice_id');
            $model->transport_type_id   = $request->get('transport_type_id');
            $model->etd                 = $request->get('etd');
            $model->eta                 = $request->get('eta');
            $model->origin_id           = $request->get('origin_id');
            $model->destination_id      = $request->get('destination_id');
            $model->ref_code            = $request->get('ref_code');
            $model->vehicle_code_num    = $request->get('vehicle_code_num');
            $model->vehicle_plate_num   = $request->get('vehicle_plate_num');
            $model->shipper_id          = $request->get('shipper_id');
            $model->shipper_address     = $request->get('shipper_address');
            $model->warehouse_id        = $request->get('warehouse_id');
            $model->pickup_order        = $request->get('pickup_order');
            if($model->type == 'inbound'){
                $model->consignee_id        = $request->get('consignee_id');
                $model->consignee_address   = $request->get('consignee_address');
            } else {
                $an = AdvanceNotice::where('id', $request->get('advance_notice_id'))->first();
                $model->consignee_id = $an->consignee_id;
                $model->consignee_address   = $request->get('consignee_address');
            }
            
            $model->employee_name       = $request->get('employee_name');
            $model->user_id             = Auth::user()->id;
            $model->project_id          = session()->get('current_project')->id;
            $model->origin_address      = $request->get('origin_address');
            $model->origin_postcode     = $request->get('origin_postcode');
            $model->origin_latitude     = $request->get('origin_latitude');
            $model->origin_longitude    = $request->get('origin_longitude');
            $model->dest_address        = $request->get('dest_address');
            $model->dest_postcode       = $request->get('dest_postcode');
            $model->dest_latitude       = $request->get('dest_latitude');
            $model->dest_longitude      = $request->get('dest_longitude');
            $model->traffic             = $request->get('traffic');
            $model->load_type           = $request->get('load_type');
            $model->owner               = $request->get('owner');
            $model->pic                 = $request->get('pic');
            /**
             * BULOG ADDITIONAL
             */
            // $model->annotation          = $request->get('annotation');
            // $model->contractor          = $request->get('contractor');
            // $model->head_ds             = $request->get('head_ds');
            /**
             * ADDITIONAL AGAIN
             */
            $model->save();


            //Generate doc code
            $user_company_id = sprintf("%04d", session()->get('current_project')->id);
            $model_code = $model->getDocCode($request->get('type'));
            $year_month = Carbon::now()->format('ym');
            $doc_id = sprintf("%04d", $model->id);
            $doc_code = $user_company_id.'.'.$model_code.'.'.$year_month.'.'.$doc_id;
            $model->code = $doc_code;
            $model->save();

            //create data log
            if($model->type == 'inbound'){
                $sub_type = 'gr';
            }
            else{
                $sub_type = 'dp';
            }

            // $data_log = array(
            //     'user_id' => Auth::user()->id,
            //     'type' => $model->type,
            //     'sub_type' => $sub_type,
            //     'record_id' => $model->id,
            //     'status' => 'start',
            // );

            // $input = array_except($data_log, '_token');
            // $DataLog = DataLog::create($input);

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->input())->with('error', 'Terjadi kesalahan saat menyimpan data. Silahkan ulangi kembali ' . $e->getMessage());
        }
        DB::commit();
        try {
            activity()
                ->performedOn($model)
                ->causedBy(Auth::user())
                ->withProperties($model)
                ->log('Planning ' . ($request->get('type') == 'inbound' ? 'Good Receiving' : 'Delivery Plan') );
        } catch (\Exception $e) {

        }
        //return redirect('stock_transports/copy_details/'.$model->id);
        return redirect('stock_transports/'.$model->id.'/edit')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\StockTransport  $stockTransport
     * @return \Illuminate\Http\Response
     */
    public function show(StockTransport $stockTransport)
    {
        $type = $stockTransport->type;
        $action = route('stock_transports.update', $stockTransport->id);
        $method = 'PUT';
        $cities = City::pluck('name','id');
        $transport_types = TransportType::pluck('name','id');
        $advance_notices = AdvanceNotice::where('type',$type)
                        ->where('project_id', session()->get('current_project')->id)
                        ->orderBy('id', 'desc')
                        ->pluck('code','id');
        if($type == 'inbound') {
            $shippers = Party::whereHas('party_types', function ($query) {
                $query->where('name','shipper');
            })->get();
            $consignees = Party::whereHas('party_types', function ($query) {
                $query->orWhereIn('name',['branch']);
            })->get();
        } else {
            $shippers = Party::whereHas('party_types', function ($query) {
                $query->orWhereIn('name',['branch']);
            })->get();
            $consignees = Party::whereHas('party_types', function ($query) {
                $query->where('name','consignee');
            })->get();
        }
        $warehouseOfficers = DB::table('users as u')
                            ->join('role_user as ru','ru.user_id', '=', 'u.id')
                            ->join('roles as r','r.id','=', 'ru.role_id')
                            ->where('r.id','=', 5)
                            ->get();
        return view('stock_transports.view',compact('warehouseOfficers','action','method','stockTransport','transport_types','advance_notices','cities','shippers','consignees','type'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StockTransport  $stockTransport
     * @return \Illuminate\Http\Response
     */
    public function edit(StockTransport $stockTransport)
    {
        $status="edit";
        $parties = Party::select(['id', 'name'])->get();

        // Filter data employee berdasarkan data branch yang sama
        if($branch = Auth::user()->branch) {
            $warehouseOfficers = DB::table('users as u')
                            ->join('role_user as ru','ru.user_id', '=', 'u.id')
                            ->join('roles as r','r.id','=', 'ru.role_id')
                            ->where('r.id','=', 5)
                            ->where('u.branch_id', '=', $branch->id)
                            ->get();
        } else {
            $warehouseOfficers = DB::table('users as u')
                            ->join('role_user as ru','ru.user_id', '=', 'u.id')
                            ->join('roles as r','r.id','=', 'ru.role_id')
                            ->where('r.id','=', 5)
                            ->get();
        }

        // Check if owner
        if(Auth::user()->id == $stockTransport->user_id) {
            $type = $stockTransport->type;
            $action = route('stock_transports.update', $stockTransport->id);
            $method = 'PUT';
            $cities = City::pluck('name','id');
            $transport_types = TransportType::pluck('name','id');

            //$total_actual_qty = $stockTransport->details->sum('qty');

            $jumlah_item_transport_no_actual = StockTransportDetail::join('stock_transports as st', 'st.id', 'stock_transport_details.stock_transport_id')
                    ->where('stock_transport_details.stock_transport_id', $stockTransport->id)
                    ->where('st.advance_notice_id', $stockTransport->advance_notice_id)
                    ->where('stock_transport_details.qty',0)
                    ->count();

            $advance_notices = AdvanceNotice::where('type',$type)
                        ->where('project_id', session()->get('current_project')->id)
                        ->orderBy('id', 'desc')
                        ->get(['code','id']);
            foreach ($advance_notices as $advance_notice) {
                $advance_notice->show = true;
            }

            $projectId = session()->get('current_project')->id;

            $warehouses = Storage::join('warehouses as w', 'w.id', '=', 'storages.warehouse_id')
                ->groupBy('w.id')
                ->get([
                    'w.id',
                    'w.name',
                    'w.code',
                ]);
            if($type == 'inbound') {    
                $shippers = Party::whereHas('party_types', function ($query) {
                    $query->where('name','shipper');
                })->get();
                $consignees = Party::whereHas('party_types', function ($query) {
                    $query->orWhereIn('name',['branch']);
                })->get();
            } else {
                $shippers = Party::whereHas('party_types', function ($query) {
                    $query->orWhereIn('name',['branch']);
                })->get();
                $consignees = Party::whereHas('party_types', function ($query) {
                    $query->where('name','consignee');
                })->get();
            }
            return view('stock_transports.edit',compact('warehouseOfficers','warehouses', 'action','method','stockTransport','transport_types','advance_notices','cities','shippers','consignees','employees','type','status','tota','jumlah_item_transport_no_actual', 'parties'));
        } else {
            return redirect()->back()->with('error','Tidak mempunyai akses');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StockTransport  $stockTransport
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StockTransport $stockTransport)
    {
        $stock = StockOpname::where('warehouse_id', session()->get('warehouse_id'))->where('status', 'Processed')->first();
        if($stock) {
            return redirect()->back()->with('error','Ada Stock Opname yang belum di Complete!');
        }
        // Check if owner
        if(Auth::user()->id == $stockTransport->user_id) {

            //Validate
            // $request->validate([
            //     'etd' => 'required|date',
            //     'eta' => 'required|date',
            //     'origin_address'    => 'required',
            //     'origin_postcode'   => 'required',
            //     'origin_latitude'   => 'required',
            //     'origin_longitude'  => 'required',
            //     'dest_address'      => 'required',
            //     'dest_postcode'     => 'required',
            //     'dest_latitude'     => 'required',
            //     'dest_longitude'    => 'required',
            // ]);

            // Note ketika update, field type, user, code tidak perlu di update
            

            $model                          = $stockTransport;
            
            // $model->advance_notice_id       = $request->get('advance_notice_id');
            // $model->transport_type_id       = $request->get('transport_type_id');
            // $model->etd                     = $request->get('etd');
            // $model->eta                     = $request->get('eta');
            // $model->origin_id               = $request->get('origin_id');
            // $model->destination_id          = $request->get('destination_id');
            // $model->ref_code                = $request->get('ref_code');
            // $model->vehicle_code_num        = $request->get('vehicle_code_num');
            // $model->vehicle_plate_num       = $request->get('vehicle_plate_num');
            // $model->shipper_id              = $request->get('shipper_id');
            // $model->shipper_address         = $request->get('shipper_address');
            // $model->warehouse_id            = $request->get('warehouse_id');
            // $model->pickup_order            = $request->get('pickup_order');
            // $model->consignee_id            = $request->get('consignee_id');
            // $model->consignee_address       = $request->get('consignee_address');
            // $model->consignee_address       = $request->get('consignee_address');
            // $model->employee_name           = $request->get('employee_name');
            // $model->project_id              = session()->get('current_project')->id;

            // $model->annotation              = $request->get('annotation');
            // $model->contractor              = $request->get('contractor');
            // $model->head_ds                 = $request->get('head_ds');

            //$model->save();

            if($model->type == 'inbound') {

                $request->validate([
                    'driver_phone'      => 'nullable|alpha_num|min:7',
                    'police_number'     => 'nullable|alpha_num|max:9',
                ]);

                $model->do_number = $request->get('do_number');
                
                if($request->hasFile('do_attachment')) {
                    $model->do_attachment = $request->do_attachment->store('do_number', 'public');
                }

                $model->driver_name = $request->get('driver_name');
                $model->driver_phone = $request->get('driver_phone');
                $model->sim_number = $request->get('sim_number');
                $model->wp_number = $request->get('wp_number');
                $model->police_number = $request->get('police_number');
                // $model->lhpk_number = $request->get('lhpk_number');
                // $model->lhpk_issue_date = $request->get('lhpk_issue_date');
                $model->fleet_arrived = $request->get('fleet_arrived');
                $model->unloading_start = $request->get('unloading_start');
                $model->unloading_end = $request->get('unloading_end');
                $model->save();
            }

            try {
                activity()
                    ->performedOn($model)
                    ->causedBy(Auth::user())
                    ->withProperties($model)
                    ->log('Update ' . ($request->get('type') == 'inbound' ? 'Good Receiving' : 'Delivery Plan') );
            } catch (\Exception $e) {

            }
            return redirect('stock_transports/'.$model->id.'/edit')->with('success', 'Data berhasil disimpan');
        } else {
            return redirect()->back()->with('error','Tidak mempunyai akses');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StockTransport  $stockTransport
     * @return \Illuminate\Http\Response
     */
    public function destroy(StockTransport $stockTransport)
    {
        // Check if owner
        if(Auth::user()->id == $stockTransport->user_id) {
            $type = $stockTransport->type;
            $stockTransport->delete();

            try {
                activity()
                    ->performedOn($stockTransport)
                    ->causedBy(Auth::user())
                    ->withProperties($stockTransport)
                    ->log('Delete ' . ($stockTransport->type == 'inbound' ? 'Good Receiving' : 'Delivery Plan') );
            } catch (\Exception $e) {

            }
            return redirect('stock_transports/'.$type)->with('success','Data berhasil dihapus');
        } else {
            return redirect()->back()->with('error','Tidak mempunyai akses');
        }
    }

    public function print(StockTransport $stockTransport)
    {
        try {
            activity()
                ->performedOn($stockTransport)
                ->causedBy(Auth::user())
                ->withProperties($stockTransport)
                ->log('Processed ' . ($stockTransport->type == 'inbound' ? 'Good Receiving' : 'Delivery Plan') );
        } catch (\Exception $e) {

        }
        return view('stock_transports.print_unloading', compact('stockTransport'));
    }

    public function completed(StockTransport $stockTransport, Request $request) {
        if(Auth::user()->id != $stockTransport->user_id) {
            abort(403);
        }
        
        $request->validate([
            'password' => 'required|string',
        ]);

        $hasher = app('hash');
        if (!$hasher->check($request->input('password'), Auth::user()->password)) {
            return redirect()->back()->with('error','Password salah');
        }
        

        if($stockTransport->type == 'inbound'){
            $sub_type = 'gr';
        }
        else{
            $sub_type = 'dp';
        }

        //create data log
        // $data_log = array(
        //     'user_id' => Auth::user()->id,
        //     'type' => $stockTransport->type,
        //     'sub_type' => $sub_type,
        //     'record_id' => $stockTransport->id,
        //     'status' => 'completed',
        // );

        // $input = array_except($data_log, '_token');
        // $DataLog = DataLog::create($input);


        try {
            activity()
                ->performedOn($stockTransport)
                ->causedBy(Auth::user())
                ->withProperties($stockTransport)
                ->log('Completed ' . ($stockTransport->type == 'inbound' ? 'Good Receiving' : 'Delivery Plan') );
        } catch (\Exception $e) {

        }

        $stockTransport->status = 'Completed';
        $stockTransport->save();

        return redirect('stock_transports/'.$stockTransport->id.'/show')->with('success','Successfully completed');
        //return redirect()->back()->with('success','Successfully completed');
    }

    public function getJson($id)
    {
        $stockTransport = StockTransport::findOrFail($id)->load('consignee');
        
        //ketika transport dan entry petugas gudang yg dapat ditugaskan hanya officer
        $stockTransport->warehouseOfficers = Warehouse::join('warehouse_officer as wo', 'wo.warehouse_id', '=', 'warehouses.id')->join('users as u', 'u.id', '=', 'wo.user_id')
                            ->join('role_user as ru','ru.user_id', '=', 'u.id')
                            ->join('roles as r','r.id','=', 'ru.role_id')
                            ->where('r.id','=', 5)
                            ->where('warehouses.id','=', $stockTransport->warehouse->id)
                            ->get(['u.first_name', 'u.last_name', 'u.id', 'u.user_id']);

        return response()->json($stockTransport);
    }

    public function sendAPI(StockTransport $stockTransport)
    {
        $commodity = NULL;
        $total_pcs = 0;
        $total_weight = 0;
        $total_volume = 0;
        foreach ($stockTransport->details as $detail) {
            $total_weight += round($detail->weight,2);
            $total_pcs += round($detail->qty,2);
            $total_volume += round($detail->volume,2);
            // Ambil satuan dan komoditas yang terakhir saja
            $uom = $detail->uom;
            $commodity = $detail->item->commodity;
        }

        try {
            $client = new \GuzzleHttp\Client();
            $url = "https://api-bgr.itgps.co.id/auth";
            
            $response = $client->request('POST', 'https://api-bgr.itgps.co.id/auth', [
                'form_params' => [
                    'username' => 'superadmin',
                    'password' => 'b6120ff1ce',
                ]
            ]);
            
            $array = json_decode($response->getBody()->getContents(), true);

            $body = [
                'nomor' => $stockTransport->code,
                'capacity' => [
                    'pcs' => [
                        'gross' => $total_pcs,
                        'actual' => 0,
                        'satuan' => $uom->name,
                    ],
                    'weight' => [
                        'gross' => $total_weight,
                        'actual' => 0,
                        'satuan' => 'Kg',
                    ],
                    'volume' => [
                        'gross' => (int)number_format($total_volume, 2, '.', ','),
                        'actual' => 0,
                        'satuan' => 'm3',
                    ],
                ],
                'shipperCode' => '10000001',
                'shipperName' => $stockTransport->shipper->name,
                'shipperAddress' => $stockTransport->shipper_address,
                'shipperPostcode' => $stockTransport->shipper->postal_code,
                'commodity' => $commodity->name,
                'description' => NULL,
                'consigneeCode' => '10000004',
                'consigneeName' => $stockTransport->consignee->name,
                'consigneeAddress' => $stockTransport->consignee_address,
                'consigneePostcode' => $stockTransport->consignee->postal_code,
                'type' => (int)request('load_type'),
                'traffic' => (int)request('traffic'),
                'originName' => $stockTransport->origin->name,
                'originAddress' => request('origin_address'),
                'originPostcode' => request('origin_postcode'),
                'originCoordinate' => [
                    'type' => 'Point',
                    'coordinates' => [(float)request('origin_longitude'), (float)request('origin_latitude')]
                ],
                'destinationName' => $stockTransport->destination->name,
                'destinationAddress' => request('dest_address'),
                'destinationPostcode' => request('dest_postcode'),
                'destinationCoordinate' => [
                    'type' => 'Point',
                    'coordinates' => [(float)request('dest_longitude'), (float)request('dest_latitude')]
                ],
                'start' => $stockTransport->etd,
                'until' => $stockTransport->eta,
                'status' => 1
            ];

            $headers = [
                'Authorization' => 'Bearer ' . $array['data']['token']
            ];

            $client_post = new \GuzzleHttp\Client();
            $r = $client_post->request('POST', 'https://api-bgr.itgps.co.id/gigr', [
                'headers' => $headers, 
                'form_params' => $body
            ]);
            
            $result = json_decode($r->getBody()->getContents(), true);

            if($result) {
                $is_saved = RecFom::create([
                    'stock_transport_id' => $stockTransport->id,
                    'foms_id' => $result['data']['id'],
                    'response' => json_encode($result['data'])
                ]);

                if($is_saved) {
                    $stockTransport->is_sent = 1;
                    $stockTransport->load_type = request('load_type');
                    $stockTransport->traffic = request('traffic');
                    $stockTransport->origin_address = request('origin_address');
                    $stockTransport->origin_postcode = request('origin_postcode');
                    $stockTransport->origin_longitude = (float)request('origin_longitude');
                    $stockTransport->origin_latitude = (float)request('origin_latitude');
                    $stockTransport->dest_address = request('dest_address');
                    $stockTransport->dest_postcode = request('dest_postcode');
                    $stockTransport->dest_longitude = (float)request('dest_longitude');
                    $stockTransport->dest_latitude = (float)request('dest_latitude');
                    $stockTransport->save();
                }

            }
                
        } catch (\Exception $e) {
            if ($e->getResponse()->getStatusCode() == 400) {
                return redirect()->back()->with('error','Bad Request');
            }
            return redirect()->back()->with('error', $e->getResponse()->getReasonPhrase());
        }
        return redirect()->back()->with('success','Successfully send Data');
    }

}
