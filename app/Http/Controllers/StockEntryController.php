<?php

namespace App\Http\Controllers;

use App\StockTransport;
use App\StockEntry;
use App\StockEntryDetail;
use App\StockTransportDetail;
use App\Warehouse;
use App\Party;
use App\DataLog;
use App\PartyType;
use App\Storage;
use Auth;
use Carbon;
use Response;
use App\Company;
use App\StockOpname;
use App\User;
use App\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use App\Mail\InboundGoodStoredStock;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StockEntryDetailExport;

class StockEntryController extends Controller
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
    public function index($type = 'inbound', Request $request)
    {
	/*$collections = StockEntry::where('type',$type)
                        ->where('project_id', session()->get('current_project')->id)
                        ->orderBy('id', 'desc')
                        ->get();
	*/
	$collections = StockEntry::where('stock_entries.type',$type)
                        ->join('stock_transports', 'stock_transports.id', 'stock_entries.stock_transport_id', 'stock_entries.status')
                        ->join('stock_advance_notices', 'stock_advance_notices.id', 'stock_transports.advance_notice_id')
                        ->select([
                         'stock_entries.id',
                         'stock_entries.stock_transport_id',
                         'stock_entries.code',
                         'stock_transports.code as transport_code', 'stock_entries.user_id as user_id',
                         'stock_entries.employee_name',
                         'stock_entries.status',
                         'stock_transports.consignee_id', 'stock_transports.shipper_id',
                         'stock_entries.ref_code',
			])
                        ->where('stock_entries.project_id', session()->get('current_project')->id)
                        ->where('stock_advance_notices.advance_notice_activity_id','<>', 20)
                        ->with(['stock_transport.consignee', 'stock_transport.destination'])
                        ->orderBy('stock_entries.id', 'desc');
                        

        // 1. Kalau ada data cabang, filter datanya
        if(Auth::user()->branch && !Auth::user()->hasRole('CargoOwner')) {
            // 1. Kalau inbound, keluarkan data dimana consignee equals to user branch data
            if($type == 'inbound') {
                $collections->where('stock_transports.consignee_id', Auth::user()->branch->id);
		/*$collections = $collections->filter(function ($item) {
                    return $item->stock_transport->consignee_id == Auth::user()->branch->id;
                });*/
            } else {
                // 2. Kalau outbound, keluarkan data dimana shipper equals to user branch data
		        $collections->where('stock_transports.shipper_id', Auth::user()->branch->id); 
                /*$collections = $collections->filter(function ($item) {
                    return $item->stock_transport->shipper_id == Auth::user()->branch->id;
                });*/
            }

            // 2. Kalau yg login WH supervisor, di filter lg datanya berdasarkan yang di assign ke dia saja
            if(Auth::user()->hasRole('WarehouseSupervisor')) {
		    $collections->where('stock_entries.user_id', Auth::user()->id);   
		//$collections->where('warehouse_id', session()->get('warehouse_id'));

                /*$collections = $collections->filter(function ($item) {
                    
                    return $item->user_id == Auth::user()->id;
                });

                $collections = $collections->filter(function ($item) {
                    return $item->warehouse_id == session()->get('warehouse_id');
                });*/
            }
            if(Auth::user()->hasRole('Transporter')) {
                // dd('dsa');
                $collections->leftJoin('stock_deliveries', 'stock_deliveries.stock_entry_id', 'stock_entries.id')
                ->where('stock_advance_notices.advance_notice_activity_id','<>', '10')
                ->whereNull('stock_deliveries.id');
            }
        }        
        if($request->get('submit')) {
            // dd('masuk');
            return Excel::download(new StockEntryDetailExport($type), 'List ' .$type.' '.Carbon::now()->format('d-m-y').'.xlsx');

        } else {
            $collections = $collections->with('stock_transport')->get();
            return view('stock_entries.index',compact('collections','type'));	
        }
    // dd($collections[0]);
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
        $action = route('stock_entries.store');
        $method = 'POST';
        $projectId = session()->get('current_project')->id;

        $validate = \App\InternalMovement::where('project_id', $projectId)
        ->where('warehouse_id', session()->get('warehouse_id'))
        ->where('status', 'Processed')->first();
        if($validate) {
            return redirect()->back()->with('error','Ada Movement yang belum di Complete!');
        }

        // Ambil data ID stock transport yang sudah dibuatkan stock entry nya
        $collections = StockEntry::where('type',$type)
                        ->where('project_id', $projectId)
                        ->where('user_id', Auth::user()->id)
                        ->orderBy('id', 'desc')
                        ->get(['stock_transport_id']);

        $filterData = [];
        foreach ($collections as $collection) {
            array_push($filterData, intval($collection->stock_transport_id));
        }

        //return $filterData;

        //jika stock entry sudah terdaftar, maka data stock transport stock entry tersebut tidak muncul
        // $stock_transports = StockTransport::where('type',$type)
        //                 ->where('project_id', $projectId)
        //                 ->where('user_id', Auth::user()->id)
        //                 ->whereNotIn('id', $filterData)
        //                 ->where('status','Completed')
        //                 ->orderBy('id', 'desc')
        //                 ->pluck('code','id');

        $stock_transports = StockTransport::where('stock_transports.type',$type)
        ->join('stock_advance_notices', 'stock_advance_notices.id', 'stock_transports.advance_notice_id')
        ->where('advance_notice_activity_id','<>', 20)

        ->where('stock_transports.project_id', $projectId)
        ->where('stock_transports.user_id', Auth::user()->id)
        ->whereNotIn('stock_transports.id', $filterData)
        ->where('stock_transports.status','Completed')
        ->orderBy('stock_transports.id', 'desc')
        ->pluck('stock_transports.code','stock_transports.id');
                        // dd($stock_transports);

        // Filter data employee berdasarkan data branch yang sama
        if($branch = Auth::user()->branch) {
            $warehouses = Storage::join('warehouses as w', 'w.id', '=', 'storages.warehouse_id')
            ->where('storages.project_id', $projectId)
            ->where('w.branch_id', $branch->id)
            ->groupBy('w.id')
            ->get([
                'w.id',
                'w.name',
                'w.code',
            ]);

        } else {
            $warehouses = Storage::join('warehouses as w', 'w.id', '=', 'storages.warehouse_id')
            ->where('storages.project_id', $projectId)
            ->groupBy('w.id')
            ->get([
                'w.id',
                'w.name',
                'w.code',
            ]);
        }

        $stockEntry = new StockEntry;
        return view('stock_entries.create',compact('action','method','stock_transports','type','stockEntry','warehouses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $stock = StockOpname::where('warehouse_id', session()->get('warehouse_id'))->where('status', 'Processed')->first();
        if($stock) {
            return redirect()->back()->with('error','Ada Stock Opname yang belum di Complete!');
        }

        $projectId = session()->get('current_project')->id;

        $validate = \App\InternalMovement::where('project_id', $projectId)
        ->where('warehouse_id', session()->get('warehouse_id'))
        ->where('status', 'Processed')->first();
        if($validate) {
            return redirect()->back()->with('error','Ada Movement yang belum di Complete!');
        }
        //Validate
        $request->validate([
            'stock_transport_id' => 'required',
            // 'warehouse_id' => 'required',
            'ref_code' => 'required',
            'total_pallet' => 'nullable|numeric',
            'total_labor' => 'nullable|numeric',
            'total_forklift' => 'nullable|numeric',
            'forklift_start_time' => 'nullable|date_format:Y-m-d H:i',
            'forklift_end_time' => 'nullable|date_format:Y-m-d H:i',
            'total_koli' => 'nullable',
            'total_berat' =>'nullable|numeric',
            'total_volume' => 'nullable|numeric'
        ]);

        $stock_transport_id = $request->get('stock_transport_id');

        $check_stock_transport = StockEntry::where('stock_transport_id',$stock_transport_id)
                        ->get();

        if($check_stock_transport->count()>0){
            return redirect()->back()->with('error','dokumen referensi sudah pernah dibuat.');
        }

        $sti = StockTransport::findOrFail($stock_transport_id);

        //return $request->get('employee_name');

        $model = new StockEntry;
        $model->type = $request->get('type');
        $model->stock_transport_id = $sti->id;
        $model->total_pallet = $request->get('total_pallet');
        $model->total_labor = $request->get('total_labor');
        $model->total_forklift = $request->get('total_forklift');
        $model->forklift_start_time = $request->get('forklift_start_time');
        $model->forklift_end_time = $request->get('forklift_end_time');
        $model->ref_code = $request->get('ref_code');
        $model->employee_name = $request->get('employee_name');
        $model->warehouse_id = $sti->warehouse_id;
        $model->status = 'Processed';
        $model->user_id = Auth::user()->id;
        $model->project_id = session()->get('current_project')->id;
        $model->created_at = Carbon::now();
        $model->owner = $sti->owner;
        $model->pic = $sti->pic;
        $model->total_volume = $request->total_volume;
        $model->total_koli = $request->total_koli;
        $model->total_berat = $request->total_berat;
        $model->save();

        //Generate doc code
        $user_company_id = sprintf("%04d", session()->get('current_project')->id);
        $model_code = $model->getDocCode($request->get('type'));
        $year_month = Carbon::now()->format('ym');
        $doc_id = sprintf("%04d", $model->id);
        $doc_code = $user_company_id.'.'.$model_code.'.'.$year_month.'.'.$doc_id;
        $model->code = $doc_code;
        $model->save();

        if($model->type == 'inbound'){
            $sub_type = 'pa';
        }
        else{
            $sub_type = 'pp';
        }

        //create data log
        // $data_log = array(
        //     'user_id' => Auth::user()->id,
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
                ->log('Planning ' . ($request->get('type') == 'inbound' ? 'Put Away' : 'Picking Plan') );
        } catch (\Exception $e) {

        }

        //data item di GR/DP di copy ke PA/PP ketika buat baru
        return redirect('stock_entries/copy_details/'.$model->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\StockEntry  $stockEntry
     * @return \Illuminate\Http\Response
     */
    public function show(StockEntry $stockEntry)
    {
        if(Auth::user()->hasRole('Transporter')){
            $stockEntry->is_seen = 1;
            $stockEntry->save();
        }
        $type = $stockEntry->type;
        return view('stock_entries.view',compact('stockEntry','type'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StockEntry  $stockEntry
     * @return \Illuminate\Http\Response
     */
    public function edit(StockEntry $stockEntry)
    {
        // Check if owner
        if(Auth::user()->id == $stockEntry->user_id) {
            if ($stockEntry->status == 'publish') {
                return redirect()->back()->with('error','Sudah di publish. Tidak bisa diubah.');
            }
            $projectId = session()->get('current_project')->id;
            $id = $stockEntry->id;
            $type = $stockEntry->type;
            $action = route('stock_entries.update', $stockEntry->id);
            $method = 'PUT';

            //return $stockEntry->details;

            $stock_transports = StockTransport::where('id',$stockEntry->stock_transport_id)->pluck('code','id');

            // Filter data employee berdasarkan data branch yang sama
            if($branch = Auth::user()->branch) {
                $warehouses = Storage::join('warehouses as w', 'w.id', '=', 'storages.warehouse_id')
                ->where('storages.project_id', $projectId)
                ->where('w.branch_id', $branch->id)
                ->groupBy('w.id')
                ->get([
                    'w.id',
                    'w.name',
                    'w.code',
                ]);
                $warehouseOfficers = User::join('role_user as ru','ru.user_id', '=', 'users.id')
                                    ->join('roles as r','r.id','=', 'ru.role_id')
                                    ->where('r.id','=', 5)
                                    ->where('users.branch_id', '=', $branch->id)
                                    ->get(['users.first_name', 'users.last_name', 'users.user_id']);
            } else {
                $warehouses = Storage::join('warehouses as w', 'w.id', '=', 'storages.warehouse_id')
                ->where('storages.project_id', $projectId)
                ->groupBy('w.id')
                ->get([
                    'w.id',
                    'w.name',
                    'w.code',
                ]);
                $warehouseOfficers = User::join('role_user as ru','ru.user_id', '=', 'users.id')
                                    ->join('roles as r','r.id','=', 'ru.role_id')
                                    ->where('r.id','=', 5)
                                    ->get(['users.first_name', 'users.last_name', 'users.user_id']);
            }


            //hide tombol complete jika jumlah qty item yg di entry lebih kecil daripada item yg di transport
            if($stockEntry->type=='inbound'){
                $jumlah_item_transport = StockTransportDetail::where('stock_transport_id', $stockEntry->stock_transport_id)
                ->sum('qty');
            }
            else{
                $jumlah_item_transport = StockTransportDetail::where('stock_transport_id', $stockEntry->stock_transport_id)
                ->sum('plan_qty');
            }



            //return $request->get('item_sed');
            // dd($inbound);
            $jumlah_item_entry = StockEntryDetail::join('stock_entries as se', 'se.id', 'stock_entry_details.stock_entry_id')
                ->where('se.id', $stockEntry->id)
                ->where('stock_entry_details.status', '<>', 'canceled')
                ->sum('qty');

            // dd($outbound);
            $outstanding    = $jumlah_item_transport - $jumlah_item_entry;

            //return $jumlah_item_entry;
            
            //return $jumlah_item_entry_incompleted;

            //cari item entry yg belum dapat storage
            $jumlah_item_entry_no_storage = StockEntryDetail::join('stock_entries as se', 'se.id', 'stock_entry_details.stock_entry_id')
                ->where('stock_entry_details.stock_entry_id', $stockEntry->id)
                ->where('se.stock_transport_id', $stockEntry->stock_transport_id)
                ->where('stock_entry_details.status', '<>', 'canceled')
                ->where('stock_entry_details.storage_id',null)
                ->count();            

            return view('stock_entries.edit',compact('warehouseOfficers', 'action','method','stock_transports','type','stockEntry','warehouses','id','outstanding','jumlah_item_entry_no_storage'));
        } else {
            return redirect()->back()->with('error','Tidak mempunyai akses');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StockEntry  $stockEntry
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StockEntry $stockEntry)
    {
        //Validate
        if(Auth::user()->id != $stockEntry->user_id) {
            abort(403);
        }

        $request->validate([
            'total_pallet' => 'nullable|numeric',
            'total_labor' => 'nullable|numeric',
            'total_forklift' => 'nullable|numeric',
            'forklift_start_time' => 'nullable|date_multi_format:"Y-m-d H:i", "Y-m-d H:i:s"',
            'forklift_end_time' => 'nullable|date_multi_format:"Y-m-d H:i", "Y-m-d H:i:s"'
        ]);

        // Note ketika update, field type, user, code tidak perlu di update
        $model = $stockEntry;
        $model->total_pallet = $request->get('total_pallet');
        $model->total_labor = $request->get('total_labor');
        $model->total_forklift = $request->get('total_forklift');
        $model->forklift_start_time = $request->get('forklift_start_time');
        $model->forklift_end_time = $request->get('forklift_end_time');
        $model->save();

        try {
            activity()
                ->performedOn($model)
                ->causedBy(Auth::user())
                ->withProperties($model)
                ->log('Edit ' . ($request->get('type') == 'inbound' ? 'Put Away' : 'Picking Plan') );
        } catch (\Exception $e) {

        }

        return redirect('stock_entries/'.$model->id.'/edit')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StockEntry  $stockEntry
     * @return \Illuminate\Http\Response
     */
    public function destroy(StockEntry $stockEntry)
    {
        // Check if owner
        if(Auth::user()->id == $stockEntry->user_id) {
            $type = $stockEntry->type;
            $stockEntry->delete();

            try {
                activity()
                    ->performedOn($stockEntry)
                    ->causedBy(Auth::user())
                    ->withProperties($stockEntry)
                    ->log('Planning ' . ($stockEntry->type == 'inbound' ? 'Put Away' : 'Picking Plan') );
            } catch (\Exception $e) {

            }
            return redirect('stock_entries/'.$type)->with('success','Data berhasil dihapus');
        } else {
            return redirect()->back()->with('error','Tidak mempunyai akses');
        }
    }

    public function printPickingPlan($id){
        $data = StockEntry::find($id);
        try {
            activity()
                ->performedOn($data)
                ->causedBy(Auth::user())
                ->withProperties($data)
                ->log('Planning ' . ($data->type == 'inbound' ? 'Put Away' : 'Picking Plan') );
        } catch (\Exception $e) {

        }
        return view('stock_entries.printPickingPlan', compact('data'));
    }

    public function print(StockEntry $stockEntry){
        try {
            activity()
                ->performedOn($stockEntry)
                ->causedBy(Auth::user())
                ->withProperties($stockEntry)
                ->log('Processed ' . ($stockEntry->type == 'inbound' ? 'Put Away' : 'Picking Plan') );
        } catch (\Exception $e) {

        }

        return view('stock_entries.print_detail',compact('stockEntry'));
    }

    public function getJson($id)
    {
        try{
            $data = collect(['error' => false]);
            $stockEntry = StockEntry::findOrFail($id);
            $stockTransport = StockTransport::select([
                    'vehicle_code_num',
                    'vehicle_plate_num',
                    'shipper_id',
                    'shipper_address',
                    'consignee_id',
                    'consignee_address',
                    'origin_id',
                    'destination_id',
                    'etd',
                    'transport_type_id',
                    'eta',
                    'employee_name',
                ])
                ->findOrFail($stockEntry->stock_transport_id);
            $data = $data->merge($stockEntry);
            $data = $data->merge($stockTransport);
        }catch(ModelNotFoundException $e){
            return ['error' => true];
        }
        return $data;
        // $stockEntry = StockEntry::findOrFail($id);
        // return response()->json($stockEntry);
    }

    public function copyDetails(StockEntry $stockEntry)
    {
        $item_details = $stockEntry->stock_transport->details;
        $projectId = session()->get('current_project')->id;
        //IMPORTANT!!!
        //$storage = Storage::first(['id']);

        //return $storage;
        
        if (empty($stockEntry->stock_transport)) {
            return redirect('stock_entries/'.$stockEntry->id.'/edit')->with('error','Anda tidak memiliki data item transport. Silahkan tambahkan data item transport terlebih dahulu.');
        }
        if($item_details->count()>0) {
            foreach ($item_details as $detail) {
                // dd($detail);
                if($stockEntry->type == 'inbound') {
                    $qty = $detail->qty;
                }
                else{
                    $qty = $detail->plan_qty;
                }
                
                $itemId = $detail->item_id;
                $stockEntryId = $stockEntry->id;
                $stockEntry = StockEntry::find($stockEntryId);

                if($stockEntry->type == 'inbound') {
                    // Assign first available storage
                    $model = new StockEntryDetail;
                    $model->stock_entry_id = $stockEntryId;
                    $model->item_id = $itemId;
                    $model->uom_id = $detail->uom_id;
                    $model->begining_qty = 0;
                    $model->qty = $qty;
                    $model->ending_qty = 0;
                    $model->weight = empty($detail->weight) ? 0 : $detail->weight;
                    $model->volume = empty($detail->volume) ? 0 : $detail->volume;
                    $model->control_date = $detail->control_date;
                    $model->ref_code = $detail->ref_code;
                    $model->status = 'draft';
                    //$model->storage_id = $storage->id;
                    $model->storage_id = null;
                    $model->warehouse_id = $stockEntry->warehouse_id;
                    $model->save();
                } else {
                    
                    $model = new StockEntryDetail;
                    $model->stock_entry_id = $stockEntryId;
                    $model->item_id = $itemId;
                    $model->uom_id = $detail->uom_id;
                    $model->begining_qty = 0;
                    $model->qty = $qty;
                    $model->ending_qty = 0;
                    $model->weight = empty($detail->plan_weight) ? 0 : $detail->plan_weight;
                    $model->volume = empty($detail->plan_volume) ? 0 : $detail->plan_volume;
                    //$model->control_date = $detail->control_date;
                    $model->control_date = null;
                    $model->ref_code = $detail->ref_code;
                    $model->status = 'draft';
                    $model->storage_id = NULL;
                    $model->warehouse_id = $stockEntry->warehouse_id;
                    $model->save();
                }
            }
        }
        return redirect('stock_entries/'.$stockEntry->id.'/edit')->with('success','Daftar barang berhasil di copy');
    }

    public function status(StockEntry $stockEntry, $status, Request $request){
        
        if(Auth::user()->id != $stockEntry->user_id) {
            abort(403);
        }

        $request->validate([
            'password' => 'required|string',
        ]);

        $hasher = app('hash');
        if (!$hasher->check($request->input('password'), Auth::user()->password)) {
            return redirect()->back()->with('error','Password salah');
        }

        $stockEntry->status = $status;
        $stockEntry->save();

        try {
            activity()
                ->performedOn($stockEntry)
                ->causedBy(Auth::user())
                ->withProperties($stockEntry)
                ->log($status . ' ' . ($stockEntry->type == 'inbound' ? 'Put Away' : 'Picking Plan') );
        } catch (\Exception $e) {

        }

        return redirect()->back()->with('success', 'Successfully '.$status );
    }

    public function getJsonData($id) {
        try{
            $data = collect(['error' => false]);
            $stockEntry = StockEntry::findOrFail($id);
            $stockTransport = StockTransport::select([
                    'vehicle_code_num',
                    'vehicle_plate_num',
                    'shipper_id',
                    'shipper_address',
                    'consignee_id',
                    'consignee_address',
                    'origin_id',
                    'destination_id',
                    'etd',
                    'eta',
                    'employee_name',
                ])
                ->findOrFail($stockEntry->stock_transport_id);
            $data = $data->merge($stockEntry);
            $data = $data->merge($stockTransport);
        }catch(ModelNotFoundException $e){
            return ['error' => true];
        }
        return $data;
    }

}
