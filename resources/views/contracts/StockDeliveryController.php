<?php

namespace App\Http\Controllers;

use App\StockDelivery;
use App\StockDeliveryDetail;
use App\StockEntry;
use App\StockEntryDetail;
use App\City;
use App\TransportType;
use App\Party;
use App\PartyType;
use App\User;
use Auth;
use Carbon;
use Response;
use App\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use App\Mail\DeliveryNoteCreated;

class StockDeliveryController extends Controller
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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type = 'inbound')
    {
            $userId     = Auth::user()->id;

            $role       = DB::table('role_user')
                        ->where('role_user.user_id','=',$userId)
                        ->join('roles', 'role_user.role_id', '=', 'roles.id')
                        ->select('roles.id as roleId')
                        ->first();

        if (Auth::user()->hasRole('Admin-BGR') || Auth::user()->hasRole('WarehouseSupervisor') || Auth::user()->hasRole('WarehouseOfficer') ) {
        $collections    = StockDelivery::where('type',$type)
                            ->where('project_id', session()->get('current_project')->id)
                            ->whereIn('status', ['Completed', 'Closed', 'Pending'])          
                            ->orderBy('id', 'desc')
                            ->get();            
        } else {
        
        $collections    = StockDelivery::where('type',$type)
                            ->where('project_id', session()->get('current_project')->id)                
                            ->orderBy('id', 'desc')
                            ->where('status', '<>', 'Closed')
                            ->get();
        } 


                        // dd($collections);
        return view('stock_deliveries.index',compact('collections','type', 'userId', 'role'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($type = 'inbound')
    {
        $action = route('stock_deliveries.store');
        $method = 'POST';
        $cities = City::pluck('name','id');
        $transport_types = TransportType::pluck('name','id');
        $stock_entries = StockEntry::where('type',$type)
                        ->where('project_id', session()->get('current_project')->id)
                        ->orderBy('id', 'desc')
                        ->where('status', 'Completed')
                        ->pluck('code','id');
        $shippers = Party::whereHas('party_types', function ($query) {
            $query->where('name','shipper');
        })->get();
        $consignees = Party::whereHas('party_types', function ($query) {
            $query->where('name','consignee');
        })->get();
        $warehouseOfficers = User::join('role_user as ru','ru.user_id', '=', 'users.id')
            ->join('roles as r','r.id','=', 'ru.role_id')
            ->where('r.id','=', 5)
            ->get(['users.first_name', 'users.last_name']);
        $stockDelivery = new StockDelivery;
        return view('stock_deliveries.create',compact('warehouseOfficers','action','method','stockDelivery','transport_types','stock_entries','cities','shippers','consignees','type'));
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
        // dd($request->all());
        $request->validate([
            
            'etd' => 'required|date',
            'eta' => 'required|date',
            'pickup_order' => 'required',
            'type' => 'in:inbound,outbound',
            'total_collie' => 'nullable|numeric',
            'total_weight' => 'nullable|numeric',
            'total_volume' => 'nullable|numeric',
            'transport_type_id' => 'required',
        ]);

        $model                          = new StockDelivery;
        $model->type                    = $request->get('type');
        $model->stock_entry_id          = $request->get('stock_entry_id');
        $model->transport_type_id       = $request->get('transport_type_id');
        $model->pickup_order            = $request->get('pickup_order');
        $model->etd                     = $request->get('etd');
        $model->eta                     = $request->get('eta');
        $model->total_collie            = $request->get('total_collie');
        $model->total_weight            = $request->get('total_weight');
        $model->total_volume            = $request->get('total_volume');
        $model->origin_id               = $request->get('origin_id');
        $model->destination_id          = $request->get('destination_id');
        $model->ref_code                = $request->get('ref_code');
        $model->vehicle_code_num        = $request->get('vehicle_code_num');
        $model->vehicle_plate_num       = $request->get('vehicle_plate_num');
        $model->shipper_id              = $request->get('shipper_id');
        $model->shipper_address         = $request->get('shipper_address');
        if ($request->get('type') == 'inbound') {
            $model->consignee_id        = $request->get('consignee_id');
            $model->consignee_address   = $request->get('consignee_address');
        }
        $model->employee_id     = $request->get('employee_id');
        $model->status          = $request->get('status');
        $model->user_id         = Auth::user()->id;
        $model->project_id      = session()->get('current_project')->id;
        $model->save();

        //Generate doc code
        $user_company_id = sprintf("%04d", session()->get('current_project')->id);
        $model_code = $model->getDocCode($request->get('type'));
        $year_month = Carbon::now()->format('ym');
        $doc_id = sprintf("%04d", $model->id);
        // $doc_code = $user_company_id.'.'.$model_code.'.'.$year_month.'.'.$doc_id;
        $doc_code = $user_company_id.'.GI.'.$year_month.'.'.$doc_id;
        $model->code = $doc_code;
        $model->save();
        
        $userCreating = StockDelivery::where('stock_deliveries.id', $model->id)
            ->join('stock_entries as se', 'se.id', '=', 'stock_deliveries.stock_entry_id')
            ->join('stock_transports as st', 'st.id', '=', 'se.stock_transport_id')
            ->join('stock_advance_notices as san', 'san.id', '=', 'st.advance_notice_id')
            ->join('users as u', 'u.id', '=', 'san.user_id')
            ->first(['u.email']);
        try{
            activity()
                ->performedOn($model)
                ->causedBy(Auth::user())
                ->withProperties($model)
                ->log('Planning ' . $request->get('type') == 'inbound' ? 'Goods Issue' : 'Goods Issue' );
        } catch (\Exception $e){

        }
            
        if (!empty($userCreating)) {
            // Mail::to($userCreating->email)->queue(new DeliveryNoteCreated());
        }
        return redirect('/stock_deliveries/copy_details/' . $model->id);
        return redirect('stock_deliveries/'.$model->id.'/edit')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\StockDelivery  $stockDelivery
     * @return \Illuminate\Http\Response
     */
    public function show(StockDelivery $stockDelivery)
    {
        $type = $stockDelivery->type;
        return view('stock_deliveries.view',compact('stockDelivery','transport_types','stock_entries','cities','shippers','consignees','employees','type','companies'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StockDelivery  $stockDelivery
     * @return \Illuminate\Http\Response
     */
    public function edit(StockDelivery $stockDelivery)
    {
        $type = $stockDelivery->type;
        $action = route('stock_deliveries.update', $stockDelivery->id);
        $method = 'PUT';
        $cities = City::pluck('name','id');
        $transport_types = TransportType::pluck('name','id');
        $stock_entries = StockEntry::where('type',$type)
                    ->where('project_id', session()->get('current_project')->id)
                    ->orderBy('id', 'desc')
                    ->where('status', 'Completed')
                    ->pluck('code','id');
        $shippers = Party::whereHas('party_types', function ($query) {
            $query->where('name','shipper');
        })->get();
        $consignees = Party::whereHas('party_types', function ($query) {
            $query->where('name','consignee');
        })->get();
        $employees = Party::whereHas('party_types', function ($query) {
            $query->where('name','employee');
        })->get();
        $warehouseOfficers = User::join('role_user as ru','ru.user_id', '=', 'users.id')
            ->join('roles as r','r.id','=', 'ru.role_id')
            ->where('r.id','=', 5)
            ->get(['users.first_name', 'users.last_name']);
        //return redirect('/stock_deliveries/copy_details/' . $model->id);
        return view('stock_deliveries.edit',compact('warehouseOfficers','action','method','stockDelivery','transport_types','stock_entries','cities','shippers','consignees','employees','type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StockDelivery  $stockDelivery
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StockDelivery $stockDelivery)
    {
        //Validate
        $request->validate([
            'etd' => 'required|date',
            'eta' => 'required|date',
            'type' => 'in:inbound,outbound',
            'total_collie' => 'nullable|numeric',
            'total_weight' => 'nullable|numeric',
            'total_volume' => 'nullable|numeric'
        ]);

        // Note ketika update, field type, user, code tidak perlu di update
        $model = $stockDelivery;
        $model->stock_entry_id = $request->get('stock_entry_id');
        $model->transport_type_id = $request->get('transport_type_id');
        $model->etd = $request->get('etd');
        $model->eta = $request->get('eta');
        $model->total_collie = $request->get('total_collie');
        $model->total_weight = $request->get('total_weight');
        $model->total_volume = $request->get('total_volume');
        $model->origin_id = $request->get('origin_id');
        $model->destination_id = $request->get('destination_id');
        $model->ref_code = $request->get('ref_code');
        $model->vehicle_code_num = $request->get('vehicle_code_num');
        $model->vehicle_plate_num = $request->get('vehicle_plate_num');
        $model->shipper_id = $request->get('shipper_id');
        $model->shipper_address = $request->get('shipper_address');
        if ($model->type == 'inbound') {
            $model->consignee_id = $request->get('consignee_id');
            $model->consignee_address = $request->get('consignee_address');
        }
        $model->employee_id = $request->get('employee_id');
        $model->project_id = session()->get('current_project')->id;
        $model->save();
        
        return redirect('stock_deliveries/'.$model->id.'/edit')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StockDelivery  $stockDelivery
     * @return \Illuminate\Http\Response
     */
    public function destroy(StockDelivery $stockDelivery)
    {
        $type = $stockDelivery->type;
        $stockDelivery->delete();
        
        activity()
            ->performedOn($stockDelivery)
            ->causedBy(Auth::user())
            ->withProperties($stockDelivery)
            ->log('Delete ' . $stockDelivery->type == 'inbound' ? 'Goods Issue' : 'Goods Issue' );
        return redirect('stock_deliveries/'.$type)->with('success','Data berhasil dihapus');
    }

    public function autocomplete(){

        $id = Input::get('id');

        $stock_entry = StockEntry::findOrFail($id);

        $results[] = [
            'id' => $stock_entry->id,
            'transport_type_id_asd' => $stock_entry->stock_transport->transport_type_id,
            'company_id' => $stock_entry->company_id,
            'etd' => $stock_entry->stock_transport->etd,
            'eta' => $stock_entry->stock_transport->eta,
            'waybill' => $stock_entry->stock_transport->waybill,
            'destination' => $stock_entry->stock_transport->destination,
            'origin' => $stock_entry->stock_transport->origin,
            'destination' => $stock_entry->stock_transport->destination,
            'ref_code' => $stock_entry->stock_transport->ref_code,
            'vehicle_code_num' => $stock_entry->stock_transport->vehicle_code_num,
            'vehicle_plate_num' => $stock_entry->stock_transport->vehicle_plate_num,
            'shipper_id' => $stock_entry->stock_transport->shipper_id,
            'shipper_address' => $stock_entry->stock_transport->shipper_address,
            'shipper_postal_code' => $stock_entry->stock_transport->shipper_postal_code,
            'consignee_id' => $stock_entry->stock_transport->consignee_id,
            'consignee_address' => $stock_entry->stock_transport->consignee_address,
            'consignee_postal_code' => $stock_entry->stock_transport->consignee_postal_code,
            'employee_id' => $stock_entry->employee_id,
        ];

        return Response::json($results);
    }

    public function copyDetails(StockDelivery $stockDelivery)
    {
        $item_details = $stockDelivery->stock_entry->details;
        if($item_details->count()>0) {
            foreach ($item_details as $detail) {
                $stockDeliveryDetail = new StockDeliveryDetail;
                $stockDeliveryDetail->stock_delivery_id = $stockDelivery->id;
                $stockDeliveryDetail->item_id = $detail->item_id;
                $stockDeliveryDetail->uom_id = $detail->uom_id;
                $stockDeliveryDetail->qty = $detail->qty;
                $stockDeliveryDetail->weight = $detail->weight;
                $stockDeliveryDetail->volume = $detail->volume;
                $stockDeliveryDetail->ref_code = $detail->ref_code;
                $stockDeliveryDetail->control_date = $detail->control_date;
                $stockDeliveryDetail->save();
            }   
        }
        return redirect('stock_deliveries/'.$stockDelivery->id.'/edit')->with('success','Daftar barang berhasil di copy.');
    }
    public function print(StockDelivery $stockDelivery){
        // if ($stockDelivery->status != 'Completed') {
        //     $stockDelivery->status = 'Processed';
        //     $stockDelivery->save();
            try {
                activity()
                    ->performedOn($stockDelivery)
                    ->causedBy(Auth::user())
                    ->withProperties($stockDelivery)
                    ->log('Print ' . 'Goods Issue' );
            } catch (\Exception $e) {
                
            }
        // }
        return view('stock_deliveries.delivery-note',compact('stockDelivery'));
    }



    public function completed(StockDelivery $stockDelivery, Request $request) {
        $request->validate([
            'password' => 'required|string',
        ]);

        $hasher = app('hash');
        if (!$hasher->check($request->input('password'), Auth::user()->password)) {
            return redirect()->back()->with('error','Password salah');
        }
        $stockDelivery->status = 'Completed';
        $stockDelivery->save();

        $stockEntryDetail = StockEntryDetail::where([
            'stock_entry_id' => $stockDelivery->stock_entry_id,
        ])->update(['status' => 'Closed']);
        try {
            activity()
                ->performedOn($stockDelivery)
                ->causedBy(Auth::user())
                ->withProperties($stockDelivery)
                ->log('Completed ' . $stockDelivery );
        } catch (\Exception $e) {
            
        }
        return redirect()->back()->with('success','AIN completed');
    }
    
    public function closed(StockDelivery $stockDelivery, Request $request) {
        $request->validate([
            'password' => 'required|string',
        ]);

        $hasher = app('hash');
        if (!$hasher->check($request->input('password'), Auth::user()->password)) {
            return redirect()->back()->with('error','Password salah');
        }
        $stockDelivery->status = 'Closed';
        $stockDelivery->save();

        $stockEntry = StockEntry::where([
            'id' => $stockDelivery->stock_entry_id,
        ])->first();

        $stockEntryDetail = StockEntryDetail::where([
            'stock_entry_id' => $stockDelivery->stock_entry_id,
        ])->update(['status' => 'Closed']);
        
        try {
            activity()
                ->performedOn($stockDelivery)
                ->causedBy(Auth::user())
                ->withProperties($stockDelivery)
                ->log('Closed ' . $stockDelivery );
        } catch (\Exception $e) {
            
        }
        return redirect()->back()->with('success','AIN Closed');
    }
}
