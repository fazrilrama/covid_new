<?php

namespace App\Http\Controllers;

use App\StockEntry;
use App\StockTransport;
use App\StockEntryDetail;
use App\StockTransportDetail;
use App\Storage;
use App\StorageProjects;
use App\Warehouse;
use App\Item;
use App\ItemProjects;
use App\Uom;
use App\DataLog;
use App\StockOpname;

use DB;
use Illuminate\Http\Request;
use Auth;
use Validator;

class StockEntryDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collections = StockEntryDetail::orderBy('id', 'desc')->get();
        return view('stock_entry_details.index',compact('collections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($stock_entry_id = NULL, Request $request)
    {
        $stock = StockOpname::where('warehouse_id', session()->get('warehouse_id'))->where('status', 'Processed')->first();
        if($stock) {
            return redirect()->back()->with('error','Ada Stock Opname yang belum di Complete!');
        }
        $projectId = session()->get('current_project')->id;
        $stockEntry = StockEntry::findOrFail($stock_entry_id);
        $action = route('stock_entry_details.store');
        $method = 'POST';

        if(Auth::user()->id != $stockEntry->user_id || $stockEntry->status == 'Completed') {
            abort(403);
        }

        //get item yg berasal dari data gr/dp            
        $items = StockTransportDetail::where('stock_transport_id', $stockEntry->stock_transport_id)->get();

        $weightVal = 0;
        $volumeVal = 0;

        if($stockEntry->type == 'inbound'){
            //pada pa, storage masih dipilih secara bebas.
            $storages = Storage::join('storage_projects', 'storages.id', '=', 'storage_projects.storage_id')
                        ->where('storage_projects.project_id',$projectId)
                        ->where('storages.warehouse_id', $stockEntry->warehouse_id)
                        ->get(['storages.code', 'storages.id','storages.warehouse_id','storage_projects.project_id']);
        }

        foreach ($items as $item) {
            //satu entry satu transport
            if($stockEntry->type == 'inbound'){
                $inbound = StockTransportDetail::join('stock_transports as st', 'st.id', '=', 'stock_transport_details.stock_transport_id')
                ->where('st.status', 'Completed')
                ->where([
                    'stock_transport_details.item_id' => $item->item_id,
                    'stock_transport_details.ref_code' => $item->ref_code,
                    'st.id' => $stockEntry->stock_transport_id,
                ])
                ->sum('stock_transport_details.qty');
            }
            //pada outbound data plan dp adalah data actual
            else{
                $inbound = StockTransportDetail::join('stock_transports as st', 'st.id', '=', 'stock_transport_details.stock_transport_id')
                ->where('st.status', 'Completed')
                ->where([
                    'stock_transport_details.item_id' => $item->item_id,
                    'stock_transport_details.ref_code' => $item->ref_code,
                    'st.id' => $stockEntry->stock_transport_id,
                ])
                ->sum('stock_transport_details.plan_qty');
            }
            
                
            $outbound_completed = StockEntryDetail::join('stock_entries as se', 'se.id', '=', 'stock_entry_details.stock_entry_id')
                ->where('se.status', 'Completed')
                ->where('se.stock_transport_id',$stockEntry->stock_transport_id)
                ->where('stock_entry_details.item_id' ,$item->item_id)
                ->where('stock_entry_details.ref_code',$item->ref_code)
                ->where('stock_entry_details.warehouse_id',$stockEntry->warehouse_id)
                ->sum('stock_entry_details.qty');

            $outbound_incompleted = StockEntryDetail::join('stock_entries as se', 'se.id', '=', 'stock_entry_details.stock_entry_id')
                ->Where('se.status', 'Processed')
                ->where('se.stock_transport_id',$stockEntry->stock_transport_id)
                ->where('stock_entry_details.item_id' ,$item->item_id)
                ->where('stock_entry_details.ref_code' ,$item->ref_code)
                ->where('stock_entry_details.warehouse_id',$stockEntry->warehouse_id)
                ->sum('stock_entry_details.qty');

            $fix_outbound = $outbound_completed+$outbound_incompleted;
            $item->item_outstanding = $inbound - $fix_outbound;       
        }

        //return $array;
        //return $projectId;
        //return $storages2;

        $stockEntryDetail = new StockEntryDetail;
        $stockEntryDetail->stock_entry_id = $stockEntry->id; // assign header ID

        return view('stock_entry_details.create',compact('action','method','stockEntryDetail','items','storages','stockEntry','weightVal','volumeVal'));
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
        $stock = StockOpname::where('warehouse_id', session()->get('warehouse_id'))->where('status', 'Processed')->first();
        if($stock) {
            return redirect()->back()->with('error','Ada Stock Opname yang belum di Complete!');
        }
        $validator = Validator::make($request->all(), [
            'qty'           => 'required|numeric',
            'weight'        => 'required|numeric',
            'volume'        => 'required|numeric',
            'control_date'  => 'required|date',
        ]);

        if ($request->qty == 0) {
            return redirect()
            ->back()
            ->withInput($request->input())
            ->with('error', 'Jumlah Item Tidak Boleh Kurang dari 1');
        }

        $qty = $request->get('qty');
        $itemId = $request->get('item_sed');
        $stockEntryId = $request->get('stock_entry_id');
        $stockEntry = StockEntry::find($stockEntryId);
        $uomId = $request->get('uom_id');
        $warehouseId = $request->get('warehouse_id');
        $controlDates = $request->get('control_date');
        $storage_id = $request->get('storage_id');
        $ref_code = $request->get('ref_code');

        $uom_name = Uom::find($uomId)->name;

        if(Auth::user()->id != $stockEntry->user_id || $stockEntry->status == 'Completed') {
            abort(403);
        }

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput($request->input())
                ->with('error', $validator->errors()->first());
        }

        //pada pa perhitungan outstanding masih berdasarkan gr
        if($stockEntry->type == 'inbound'){
            $inbound = StockTransportDetail::where('stock_transport_id', $stockEntry->stock_transport_id)
            ->where('ref_code','=',$ref_code)
            ->where('item_id','=',$itemId)
            ->sum('qty');

            //return $request->get('item_sed');
            // dd($inbound);
            $outbound_completed = StockEntryDetail::join('stock_entries as se', 'se.id', 'stock_entry_details.stock_entry_id')
                ->where('se.status', 'Completed')
                ->where('se.stock_transport_id', $stockEntry->stock_transport_id)
                ->where('stock_entry_details.ref_code', $ref_code)
                ->where('stock_entry_details.item_id',$itemId)
                ->where('stock_entry_details.warehouse_id',$stockEntry->warehouse_id)
                ->where('stock_entry_details.status', '<>', 'canceled')
                ->sum('qty');

            $outbound_incompleted = StockEntryDetail::join('stock_entries as se', 'se.id', 'stock_entry_details.stock_entry_id')
                ->Where('se.status', 'Processed')
                ->where('se.stock_transport_id', $stockEntry->stock_transport_id)
                ->where('stock_entry_details.ref_code', $ref_code)
                ->where('stock_entry_details.item_id',$itemId)
                ->where('stock_entry_details.warehouse_id',$stockEntry->warehouse_id)
                ->where('stock_entry_details.status', '<>', 'canceled')
                ->sum('qty');

            // dd($outbound);
            $fix_outbound = $outbound_completed+$outbound_incompleted;
            $outstanding    = $inbound - $fix_outbound;
            // dd($outstanding);
            if ($inbound == null) {
                return redirect()->back()
                ->withInput($request->input())
                ->with('error', 'Group Reference Tidak Sama');
            }
            if ($outstanding < $qty ) {
                return redirect()->back()
                        ->with('uom_name', $uom_name)
                        ->withInput($request->input())
                        ->with('error', 'Jumlah item melebihi jumlah sisa item di good receiving');
            }

            //if($fix_outbound < 0)
            // if($fix_outbound > 0)
            //     $beginingStock = $inbound + $fix_outbound;
            // else
            //     $beginingStock = $inbound - $fix_outbound;

            //return $stockEntryOutbound;

            //$beginingStockValue = (!empty($beginingStock) ? $beginingStock->ending_qty : 0);
            // $endingStock = $stockEntry->type == 'inbound' ? ($beginingStockValue + $qty) : ($beginingStockValue - $qty);
            
        }
        else{
            $projectId = session()->get('current_project')->id;

            $validate = \App\InternalMovement::where('project_id', $projectId)
            ->where('warehouse_id', session()->get('warehouse_id'))
            ->where('status', 'Processed')->first();
            if($validate) {
                return redirect()->back()->with('error','Ada Movement yang belum di Complete!');
            }
            //ketika create data storage dan control date pasti ada
            if($storage_id){
                $InboundControlDate = StockEntryDetail::join('stock_entries as se', 'se.id', '=',
                    'stock_entry_details.stock_entry_id')
                    ->where('stock_entry_details.item_id',$itemId)
                    ->where('stock_entry_details.ref_code',$ref_code)
                    ->where('stock_entry_details.storage_id',$storage_id)
                    ->where('stock_entry_details.control_date',$controlDates)
                    ->where('se.warehouse_id', $stockEntry->warehouse_id)
                    ->where('se.project_id',$stockEntry->project_id)
                    ->where('se.status', 'Completed')
                    ->where('se.type', 'inbound')
                    ->sum('stock_entry_details.qty');

                $OutboundControlDateCompleted = StockEntryDetail::join('stock_entries as se', 'se.id', '=', 'stock_entry_details.stock_entry_id')
                // ->join('stock_deliveries as sd','sd.stock_entry_id','se.id')
                // ->where('sd.status','Completed')
                ->where('se.project_id',$stockEntry->project_id)
                ->where('se.status', 'Completed')
                ->where('se.type', 'outbound')
                ->where('se.warehouse_id', $stockEntry->warehouse_id)
                ->where([
                    'stock_entry_details.item_id' => $itemId,
                    'stock_entry_details.ref_code' => $ref_code,
                    'stock_entry_details.storage_id' => $storage_id,
                    'stock_entry_details.control_date' => $controlDates,
                ])
                ->sum('stock_entry_details.qty');

                $OutboundControlDateInCompleted = StockEntryDetail::join('stock_entries as se', 'se.id', '=', 'stock_entry_details.stock_entry_id')  
                ->where('se.project_id',$stockEntry->project_id)
                ->where('se.status', 'Processed')
                ->where('se.type', 'outbound')
                ->where('se.warehouse_id', $stockEntry->warehouse_id)
                ->where([
                    'stock_entry_details.item_id' => $itemId,
                    'stock_entry_details.ref_code' => $ref_code,
                    'stock_entry_details.storage_id' => $storage_id,
                    'stock_entry_details.control_date' => $controlDates,
                ])
                ->sum('stock_entry_details.qty');

                $fix_outboundControlDate = $OutboundControlDateCompleted+$OutboundControlDateInCompleted;
                $outstandingControlDate = $InboundControlDate - $fix_outboundControlDate;
                //return $outstandingControlDate;
            }
            else{
                return redirect()->back()->withInput($request->input())->with('error', 'Pilih Storage terlebih dahulu');
            }


            //test1

            $inbound = StockTransportDetail::where('stock_transport_id', $stockEntry->stock_transport_id)
            ->where('ref_code','=',$ref_code)
            ->where('item_id','=',$itemId)
            ->sum('plan_qty');

            //return $request->get('item_sed');
            // dd($inbound);
            $outbound_completed = StockEntryDetail::join('stock_entries as se', 'se.id', 'stock_entry_details.stock_entry_id')
                ->where('se.status', 'Completed')
                ->where('se.stock_transport_id', $stockEntry->stock_transport_id)
                ->where('stock_entry_details.ref_code', $ref_code)
                ->where('stock_entry_details.item_id',$itemId)
                ->where('stock_entry_details.warehouse_id',$stockEntry->warehouse_id)
                ->where('stock_entry_details.status', '<>', 'canceled')
                ->sum('qty');

            $outbound_incompleted = StockEntryDetail::join('stock_entries as se', 'se.id', 'stock_entry_details.stock_entry_id')
                ->Where('se.status', 'Processed')
                ->where('se.stock_transport_id', $stockEntry->stock_transport_id)
                ->where('stock_entry_details.ref_code', $ref_code)
                ->where('stock_entry_details.item_id',$itemId)
                ->where('stock_entry_details.warehouse_id',$stockEntry->warehouse_id)
                ->where('stock_entry_details.status', '<>', 'canceled')
                ->sum('qty');

            // dd($outbound);
            $fix_outbound = $outbound_completed+$outbound_incompleted;
            $outstanding    = $inbound - $fix_outbound;
            // dd($outstanding);
            if ($inbound == null) {
                return redirect()->back()->withInput($request->input())->with('error', 'Group Reference Tidak Sama');
            }
            if ($outstanding < $qty ) {
                return redirect()->back()
                        ->withInput($request->input())
                        ->with('error', 'Jumlah item melebihi sisa item di delivery plan');
            }
            else{
                if($outstandingControlDate < $qty){

                    return redirect()->back()->withInput($request->input())->with('error', 'Jumlah item melebihi sisa item stock allocation yang bisa disimpan');
                }
            }
        }
            
        
        //$endingStock = $stockEntry->type == 'inbound' ? ($beginingStock + $qty) : ($beginingStock - $qty);

        // Ketika store check apakah sudah ada entry yang sama, jika sudah ada tambahkan saja kuantitasnya
        $existing_entry = StockEntryDetail::where('stock_entry_id',$stockEntryId)
                            ->where('item_id',$itemId)
                            ->where('ref_code',$ref_code)
                            ->where('control_date',$controlDates)
                            ->where('warehouse_id', $stockEntry->warehouse_id)
                            ->where('storage_id',$storage_id)
                            ->first();
        if($existing_entry) {
            $model = $existing_entry;
            $model->qty += $qty;
            $model->save();
        } else {
            $model = new StockEntryDetail;
            $model->stock_entry_id = $stockEntryId;
            $model->storage_id = $storage_id;
            $model->warehouse_id = $warehouseId;
            $model->item_id = $itemId;
            $model->uom_id = $request->get('uom_id');
            $model->begining_qty = 0;
            $model->qty = $qty;
            $model->ending_qty = 0;
            $model->weight = $request->get('weight');
            $model->volume = $request->get('volume');
            $model->control_date = $controlDates;
            $model->ref_code = $ref_code;
            $model->status = 'draft';
            $model->save();
        }

        //create data log
        if($stockEntry->type == 'inbound'){
            $sub_type = 'pa';
        }
        else{
            $sub_type = 'pp';
        }

        // $data_log = array(
        //     'user_id' => Auth::user()->id,
        //     'type' => $stockEntry->type,
        //     'sub_type' => $sub_type,
        //     'record_id' => $stockEntry->id,
        //     'status' => 'proccess',
        // );

        // $input = array_except($data_log, '_token');
        // $DataLog = DataLog::create($input);

        // Redirect ke view headernya langsung
        return redirect('stock_entries/'.$model->stock_entry_id.'/edit')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\StockEntryDetail  $stockEntryDetail
     * @return \Illuminate\Http\Response
     */
    public function show(StockEntryDetail $stockEntryDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StockEntryDetail  $stockEntryDetail
     * @return \Illuminate\Http\Response
     */
    public function edit(StockEntryDetail $stockEntryDetail)
    {
        $stockEntry = StockEntry::findOrFail($stockEntryDetail->stock_entry_id);
        $action = route('stock_entry_details.update', $stockEntryDetail->id);
        $method = 'PUT';

        $item = Item::find($stockEntryDetail->item_id);

        if(Auth::user()->id != $stockEntry->user_id || $stockEntry->status == 'Completed') {
            abort(403);
        }

        $weightVal = $item->weight;
        $volumeVal = $item->volume;
        
        // atas aman

        if ($stockEntryDetail->header->type == 'inbound') {

            $inbound = StockTransportDetail::join('stock_transports as st', 'st.id', '=', 'stock_transport_details.stock_transport_id')
            ->where('st.status', 'Completed')
            ->where([
                'stock_transport_details.item_id' => $item->id,
                'stock_transport_details.ref_code' => $stockEntryDetail->ref_code,
                'st.id' => $stockEntry->stock_transport_id,
            ])
            ->sum('stock_transport_details.qty');
            
            $outbound_completed = StockEntryDetail::join('stock_entries as se', 'se.id', '=', 'stock_entry_details.stock_entry_id')
                ->where('se.status', 'Completed')
                ->where([
                    'stock_entry_details.item_id' => $item->id,
                    'stock_entry_details.ref_code' => $stockEntryDetail->ref_code,
                    'se.stock_transport_id' => $stockEntry->stock_transport_id,
                    'stock_entry_details.warehouse_id' => $stockEntry->warehouse_id,
                ])
                ->sum('stock_entry_details.qty');

            $outbound_incompleted = StockEntryDetail::join('stock_entries as se', 'se.id', '=', 'stock_entry_details.stock_entry_id')
                ->where('se.status', 'Processed')
                ->where('stock_entry_details.id','<>',$stockEntryDetail->id)
                ->where([
                    'stock_entry_details.item_id' => $item->id,
                    'stock_entry_details.ref_code' => $stockEntryDetail->ref_code,
                    'se.stock_transport_id' => $stockEntry->stock_transport_id,
                    'stock_entry_details.warehouse_id' => $stockEntry->warehouse_id,
                ])
                ->sum('stock_entry_details.qty');

            $fix_outbound = $outbound_completed+$outbound_incompleted;
            $stockEntryDetail->item_outstanding = $inbound - $fix_outbound;

            //return $stockEntryDetail->item_outstanding;
            $storages = Storage::join('storage_projects', 'storages.id', '=', 'storage_projects.storage_id')
                        //->where('storages.status', 1)
                        ->where('storage_projects.project_id',$stockEntry->project_id)
                        ->where('storages.warehouse_id', $stockEntry->warehouse_id)                 
                        ->get(['storages.code', 'storages.id','storages.warehouse_id','storage_projects.project_id']);

            //return $storages;
        }
        else{

            $storages = StockEntryDetail::join('stock_entries as se', 'se.id', '=',
                        'stock_entry_details.stock_entry_id')
                        ->join('storages as s','s.id','=','stock_entry_details.storage_id')
                        ->where('stock_entry_details.item_id', $stockEntryDetail->item_id)
                        ->where('se.warehouse_id', $stockEntry->warehouse_id)
                        ->where('se.project_id', $stockEntry->project_id)
                        ->where('se.status', 'Completed')
                        ->where('se.type', 'inbound')
                        ->where('stock_entry_details.ref_code',$stockEntryDetail->ref_code)
                        ->groupBy('stock_entry_details.storage_id')
                        ->selectRaw('s.id,s.code')
                        ->get();

            $storagesId = StockEntryDetail::join('stock_entries as se', 'se.id', '=',
                        'stock_entry_details.stock_entry_id')
                        ->join('storages as s','s.id','=','stock_entry_details.storage_id')
                        ->where('stock_entry_details.item_id', $stockEntryDetail->item_id)
                        ->where('se.warehouse_id', $stockEntry->warehouse_id)
                        ->where('se.project_id', $stockEntry->project_id)
                        ->where('se.status', 'Completed')
                        ->where('se.type', 'inbound')
                        ->where('stock_entry_details.ref_code',$stockEntryDetail->ref_code)
                        ->groupBy('stock_entry_details.storage_id')
                        ->selectRaw('s.id')
                        ->pluck('s.id');

            //jika storage sudah ada
            if($stockEntryDetail->storage_id){
                $getControlDate = StockEntryDetail::join('stock_entries as se', 'se.id', '=',
                                'stock_entry_details.stock_entry_id')
                            ->where('stock_entry_details.item_id',$stockEntryDetail->item_id)
                            ->where('stock_entry_details.ref_code',$stockEntryDetail->ref_code)
                            ->where('stock_entry_details.storage_id',$stockEntryDetail->storage_id)
                            ->where('se.warehouse_id', $stockEntry->warehouse_id)
                            ->where('se.project_id', $stockEntry->project_id)
                            ->where('se.status', 'Completed')
                            ->where('se.type', 'inbound')
                            ->groupBy('stock_entry_details.item_id','stock_entry_details.ref_code','stock_entry_details.storage_id','se.warehouse_id','se.project_id','stock_entry_details.control_date')
                            ->selectRaw('stock_entry_details.control_date')

                            ->get();

                //hitung outstanding di allocation
                foreach($getControlDate as $controlDate){
                    $InboundControlDate = StockEntryDetail::join('stock_entries as se', 'se.id', '=',
                        'stock_entry_details.stock_entry_id')
                        ->where('stock_entry_details.item_id',$stockEntryDetail->item_id)
                        ->where('stock_entry_details.ref_code',$stockEntryDetail->ref_code)
                        ->where('stock_entry_details.storage_id',$stockEntryDetail->storage_id)
                        ->where('stock_entry_details.control_date',$controlDate->control_date)
                        ->where('se.warehouse_id', $stockEntry->warehouse_id)
                        ->where('se.project_id',$stockEntry->project_id)
                        ->where('se.status', 'Completed')
                        ->where('se.type', 'inbound')
                        ->sum('stock_entry_details.qty');

                    $OutboundControlDateCompleted = StockEntryDetail::join('stock_entries as se', 'se.id', '=', 'stock_entry_details.stock_entry_id')
                    // ->join('stock_deliveries as sd','sd.stock_entry_id','se.id')
                    // ->where('sd.status','Completed')
                    ->where('se.project_id',$stockEntry->project_id)
                    ->where('se.status', 'Completed')
                    ->where('se.type', 'outbound')
                    ->where('se.warehouse_id', $stockEntry->warehouse_id)
                    ->where([
                        'stock_entry_details.item_id' => $stockEntryDetail->item_id,
                        'stock_entry_details.ref_code' => $stockEntryDetail->ref_code,
                        'stock_entry_details.storage_id' => $stockEntryDetail->storage_id,
                        'stock_entry_details.control_date' => $controlDate->control_date,
                    ])
                    ->sum('stock_entry_details.qty');

                    $OutboundControlDateInCompleted = StockEntryDetail::join('stock_entries as se', 'se.id', '=', 'stock_entry_details.stock_entry_id')  
                    ->where('se.project_id',$stockEntry->project_id)
                    ->where('se.status', 'Processed')
                    ->where('se.type', 'outbound')
                    ->where('se.warehouse_id', $stockEntry->warehouse_id)
                    ->where('stock_entry_details.id','<>',$stockEntryDetail->id)
                    ->where([
                        'stock_entry_details.item_id' => $stockEntryDetail->item_id,
                        'stock_entry_details.ref_code' => $stockEntryDetail->ref_code,
                        'stock_entry_details.storage_id' => $stockEntryDetail->storage_id,
                        'stock_entry_details.control_date' => $controlDate->control_date,
                    ])
                    ->sum('stock_entry_details.qty');

                    $fix_outboundControlDate = $OutboundControlDateCompleted+$OutboundControlDateInCompleted;
                    $controlDate->qty_available = $InboundControlDate - $fix_outboundControlDate;
                }
            }
            //jika storage belum ada
            else{
                $getControlDate = StockEntryDetail::join('stock_entries as se', 'se.id', '=',
                                'stock_entry_details.stock_entry_id')
                            ->join('storages as s','s.id','=','stock_entry_details.storage_id')
                            ->where('stock_entry_details.item_id',$stockEntryDetail->item_id)
                            ->where('stock_entry_details.ref_code',$stockEntryDetail->ref_code)
                            ->where('stock_entry_details.storage_id',$storagesId->first())
                            ->where('se.warehouse_id', $stockEntry->warehouse_id)
                            ->where('se.project_id', $stockEntry->project_id)
                            ->where('se.status', 'Completed')
                            ->where('se.type', 'inbound')
                            ->groupBy('stock_entry_details.item_id','stock_entry_details.ref_code','stock_entry_details.storage_id','se.warehouse_id','se.project_id','stock_entry_details.control_date')
                            ->selectRaw('sum(stock_entry_details.qty) as qty_available,stock_entry_details.item_id,stock_entry_details.ref_code,stock_entry_details.control_date,stock_entry_details.storage_id,s.code as s_code')

                            ->get();

                //hitung outstanding di allocation
                foreach($getControlDate as $controlDate){
                    $InboundControlDate = StockEntryDetail::join('stock_entries as se', 'se.id', '=',
                        'stock_entry_details.stock_entry_id')
                        ->where('stock_entry_details.item_id',$stockEntryDetail->item_id)
                        ->where('stock_entry_details.ref_code',$stockEntryDetail->ref_code)
                        ->where('stock_entry_details.storage_id',$storagesId->first())
                        ->where('stock_entry_details.control_date',$controlDate->control_date)
                        ->where('se.warehouse_id', $stockEntry->warehouse_id)
                        ->where('se.project_id',$stockEntry->project_id)
                        ->where('se.status', 'Completed')
                        ->where('se.type', 'inbound')
                        ->sum('stock_entry_details.qty');

                    $OutboundControlDateCompleted = StockEntryDetail::join('stock_entries as se', 'se.id', '=', 'stock_entry_details.stock_entry_id')
                    // ->join('stock_deliveries as sd','sd.stock_entry_id','se.id')
                    // ->where('sd.status','Completed')
                    ->where('se.project_id',$stockEntry->project_id)
                    ->where('se.status', 'Completed')
                    ->where('se.type', 'outbound')
                    ->where('se.warehouse_id', $stockEntry->warehouse_id)
                    ->where([
                        'stock_entry_details.item_id' => $stockEntryDetail->item_id,
                        'stock_entry_details.ref_code' => $stockEntryDetail->ref_code,
                        'stock_entry_details.storage_id' => $storagesId->first(),
                        'stock_entry_details.control_date' => $controlDate->control_date,
                    ])
                    ->sum('stock_entry_details.qty');

                    $OutboundControlDateInCompleted = StockEntryDetail::join('stock_entries as se', 'se.id', '=', 'stock_entry_details.stock_entry_id')  
                    ->where('se.project_id',$stockEntry->project_id)
                    ->where('se.status', 'Processed')
                    ->where('se.type', 'outbound')
                    ->where('se.warehouse_id', $stockEntry->warehouse_id)
                    ->where('stock_entry_details.id','<>',$stockEntryDetail->id)
                    ->where([
                        'stock_entry_details.item_id' => $stockEntryDetail->item_id,
                        'stock_entry_details.ref_code' => $stockEntryDetail->ref_code,
                        'stock_entry_details.storage_id' => $storagesId->first(),
                        'stock_entry_details.control_date' => $controlDate->control_date,
                    ])
                    ->sum('stock_entry_details.qty');

                    $fix_outboundControlDate = $OutboundControlDateCompleted+$OutboundControlDateInCompleted;
                    $controlDate->qty_available = $InboundControlDate - $fix_outboundControlDate;
                }
            }
            
            //return $storages;
            //hitung outstanding di stock transport
            $inbound = StockTransportDetail::join('stock_transports as st', 'st.id', '=', 'stock_transport_details.stock_transport_id')
            ->where('st.status', 'Completed')
            ->where([
                'stock_transport_details.item_id' => $item->id,
                'stock_transport_details.ref_code' => $stockEntryDetail->ref_code,
                'st.id' => $stockEntry->stock_transport_id,
            ])
            ->sum('stock_transport_details.plan_qty');
            
            $outbound = StockEntryDetail::join('stock_entries as se', 'se.id', '=', 'stock_entry_details.stock_entry_id')
                ->where('stock_entry_details.id','<>',$stockEntryDetail->id)
                ->where([
                    'stock_entry_details.item_id' => $item->id,
                    'stock_entry_details.ref_code' => $stockEntryDetail->ref_code,
                    'stock_entry_details.warehouse_id' => $stockEntryDetail->warehouse_id,
                    'se.stock_transport_id' => $stockEntry->stock_transport_id,
                ])
                ->sum('stock_entry_details.qty');

            $stockEntryDetail->item_outstanding = $inbound - $outbound;
        }

        return view('stock_entry_details.edit',compact('action','method', 'stockEntryDetail','item','storages','stockEntry','getControlDate','weightVal','volumeVal'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StockEntryDetail  $stockEntryDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StockEntryDetail $stockEntryDetail)
    {
        $stock = StockOpname::where('warehouse_id', session()->get('warehouse_id'))->where('status', 'Processed')->first();
        if($stock) {
            return redirect()->back()->with('error','Ada Stock Opname yang belum di Complete!');
        }
        //Validate
        // dd($request->all());

        // $quantity = StockTransportDetail::all();
        $request->validate([
            'qty' => 'required',
            'weight' => 'required',
            'volume' => 'required',
            'control_date' => 'required|date',
        ]);

        if ($request->qty == 0) {
            return redirect()->back()
            ->withInput($request->input())
            ->with('error', 'Jumlah Item Tidak Boleh Kurang dari 1');
        }
        
        // if ($request->qty <> $request->stock_transport_id_ajax ) {
        //     return redirect()->back()
        //     ->with('error', 'Jumlah Item Picking Plan Tidak Boleh Melebihi Jumlah Item Delivery Plan ');
        // }

        $qty = $request->get('qty');
        $itemId = $request->get('item_sed');

        $control_date = $request->get('control_date');
        $ref_code = $request->get('ref_code');
        $storage_id = $request->get('storage_id');

        $stockEntryId = $request->get('stock_entry_id');
        $stockEntry = StockEntry::find($stockEntryId);

        $warehouseId = $request->get('warehouse_id');
        $projectId = session()->get('current_project')->id;

        $stockAllocationId = $request->get('stock_allocation_id');

        if(Auth::user()->id != $stockEntry->user_id || $stockEntry->status == 'Completed') {
            abort(403);
        }

        $item = Item::find($itemId);

        if ($stockEntry->type == 'inbound') {

            $inbound = StockTransportDetail::where('stock_transport_id', $stockEntry->stock_transport_id)
                ->where('ref_code','=',$ref_code)
                ->where('item_id','=',$itemId)
                ->sum('qty');
            // dd($inbound);
            // Exclude current entry
            $outbound_completed = StockEntryDetail::join('stock_entries as se', 'se.id', 'stock_entry_details.stock_entry_id')
                ->where('se.status','Completed')
                ->where('se.stock_transport_id', $stockEntry->stock_transport_id)
                ->where('stock_entry_details.ref_code', $ref_code)
                ->where('stock_entry_details.item_id',$itemId)
                ->where('stock_entry_details.warehouse_id', $stockEntryDetail->warehouse_id)
                ->where('stock_entry_details.status', '<>', 'canceled')
                ->sum('qty');

            $outbound_incompleted = StockEntryDetail::join('stock_entries as se', 'se.id', 'stock_entry_details.stock_entry_id')
                ->where('se.status', 'Processed')
                ->where('stock_entry_details.id','<>',$stockEntryDetail->id)
                ->where('se.stock_transport_id', $stockEntry->stock_transport_id)
                ->where('stock_entry_details.ref_code', $ref_code)
                ->where('stock_entry_details.item_id',$itemId)
                ->where('stock_entry_details.warehouse_id', $stockEntryDetail->warehouse_id)
                ->where('stock_entry_details.status', '<>', 'canceled')
                ->sum('qty');

                
            //return $outbound;
            $fix_outbound = $outbound_completed+$outbound_incompleted;
            $outstanding    = $inbound - $fix_outbound;
            // dd($outstanding);

            if ($inbound == null) {
                return redirect()->back()->withInput($request->input())->with('error', 'Group Reference Tidak Sama');
            }
            if ($outstanding < $qty) {
                return redirect()->back()->withInput($request->input())->with('error', 'Jumlah item melebihi sisa item di Good Receiving');
            }

            //if($fix_outbound < 0)
            // if($fix_outbound > 0){
            //     $beginingStock = $inbound + $fix_outbound;
            // } 
            // else{
            //     $beginingStock = $inbound - $fix_outbound;
            // } 
            // $endingStock = $stockEntry->type == 'inbound' ? ($beginingStock + $qty) : ($beginingStock - $qty);

            $model = $stockEntryDetail;
            $model->warehouse_id = $warehouseId;
            $model->storage_id = $request->get('storage_id');
            $model->item_id = $itemId;
            $model->uom_id = $request->get('uom_id');
            $model->begining_qty = 0;
            $model->qty = $qty;
            $model->ending_qty = 0;
            $model->weight = $request->get('weight');
            $model->volume = $request->get('volume');
            $model->control_date = $control_date;
            $model->ref_code = $ref_code;
            $model->status = 'draft';
            $model->save();

        } elseif ($stockEntry->type == 'outbound') {        
            $projectId = session()->get('current_project')->id;

            $validate = \App\InternalMovement::where('project_id', $projectId)
            ->where('warehouse_id', session()->get('warehouse_id'))
            ->where('status', 'Processed')->first();
            if($validate) {
                return redirect()->back()->with('error','Ada Movement yang belum di Complete!');
            }    
            //ketika update data storage dan control date pasti ada
            if($storage_id){
                $InboundControlDate = StockEntryDetail::join('stock_entries as se', 'se.id', '=',
                    'stock_entry_details.stock_entry_id')
                    ->where('stock_entry_details.item_id',$itemId)
                    ->where('stock_entry_details.ref_code',$ref_code)
                    ->where('stock_entry_details.storage_id',$storage_id)
                    ->where('stock_entry_details.control_date',$control_date)
                    ->where('se.warehouse_id', $stockEntry->warehouse_id)
                    ->where('se.project_id',$stockEntry->project_id)
                    ->where('se.status', 'Completed')
                    ->where('se.type', 'inbound')
                    ->sum('stock_entry_details.qty');

                $OutboundControlDateCompleted = StockEntryDetail::join('stock_entries as se', 'se.id', '=', 'stock_entry_details.stock_entry_id')
                // ->join('stock_deliveries as sd','sd.stock_entry_id','se.id')
                // ->where('sd.status','Completed')
                ->where('se.project_id',$stockEntry->project_id)
                ->where('se.status', 'Completed')
                ->where('se.type', 'outbound')
                ->where('se.warehouse_id', $stockEntry->warehouse_id)
                ->where([
                    'stock_entry_details.item_id' => $itemId,
                    'stock_entry_details.ref_code' => $ref_code,
                    'stock_entry_details.storage_id' => $storage_id,
                    'stock_entry_details.control_date' => $control_date,
                ])
                ->sum('stock_entry_details.qty');

                $OutboundControlDateInCompleted = StockEntryDetail::join('stock_entries as se', 'se.id', '=', 'stock_entry_details.stock_entry_id')  
                ->where('se.project_id',$stockEntry->project_id)
                ->where('se.status', 'Processed')
                ->where('se.type', 'outbound')
                ->where('se.warehouse_id', $stockEntry->warehouse_id)
                ->where('stock_entry_details.id','<>',$stockEntryDetail->id)
                ->where([
                    'stock_entry_details.item_id' => $itemId,
                    'stock_entry_details.ref_code' => $ref_code,
                    'stock_entry_details.storage_id' => $storage_id,
                    'stock_entry_details.control_date' => $control_date,
                ])
                ->sum('stock_entry_details.qty');

                $fix_outboundControlDate = $OutboundControlDateCompleted+$OutboundControlDateInCompleted;
                $outstandingControlDate = $InboundControlDate - $fix_outboundControlDate;
                //return $outstandingControlDate;
            }
            else{
                return redirect()->back()->withInput($request->input())->with('error', 'Pilih Storage terlebih dahulu');
            }

            $inbound = StockTransportDetail::where('stock_transport_id', $stockEntry->stock_transport_id)
                ->where('ref_code','=',$request->get('ref_code'))
                ->where('item_id','=',$itemId)
                ->sum('plan_qty');
            // dd($inbound);
            // Exclude current entry
            $outbound_completed = StockEntryDetail::join('stock_entries as se', 'se.id', 'stock_entry_details.stock_entry_id')
                ->where('se.status','Completed')
                ->where('se.stock_transport_id', $stockEntry->stock_transport_id)
                ->where('stock_entry_details.ref_code', $ref_code)
                ->where('stock_entry_details.item_id',$itemId)
                ->where('stock_entry_details.warehouse_id', $stockEntryDetail->warehouse_id)
                ->where('stock_entry_details.status', '<>', 'canceled')
                ->sum('qty');

            $outbound_incompleted = StockEntryDetail::join('stock_entries as se', 'se.id', 'stock_entry_details.stock_entry_id')
                ->where('se.status', 'Processed')
                ->where('stock_entry_details.id','<>',$stockEntryDetail->id)
                ->where('se.stock_transport_id', $stockEntry->stock_transport_id)
                ->where('stock_entry_details.ref_code', $ref_code)
                ->where('stock_entry_details.item_id',$itemId)
                ->where('stock_entry_details.warehouse_id', $stockEntryDetail->warehouse_id)
                ->where('stock_entry_details.status', '<>', 'canceled')
                ->sum('qty');

                
            //return $outbound;
            $fix_outbound = $outbound_completed+$outbound_incompleted;
            $outstanding    = $inbound - $fix_outbound;
            // dd($outstanding);

            if ($inbound == null) {
                return redirect()->back()->withInput($request->input())->with('error', 'Group Reference Tidak Sama');
            }
            if ($outstanding < $qty) {

                return redirect()->back()->withInput($request->input())->with('error', 'Jumlah item melebihi sisa item di delivery plan');
 
            }
            else{
                if($outstandingControlDate < $qty){

                    return redirect()->back()->withInput($request->input())->with('error', 'Jumlah item melebihi sisa item stock allocation yang bisa disimpan');
                }
            }
            

            $model = $stockEntryDetail;
            $model->warehouse_id = $warehouseId;
            $model->storage_id = $request->get('storage_id');
            $model->item_id = $itemId;
            $model->uom_id = $request->get('uom_id');
            $model->begining_qty = 0;
            $model->qty = $qty;
            $model->ending_qty = 0;
            $model->weight = $request->get('weight');
            $model->volume = $request->get('volume');
            $model->control_date = $control_date;
            $model->ref_code = $ref_code;
            $model->status = 'draft';
            $model->save();
        }

        // Redirect ke view headernya langsung
        return redirect('stock_entries/'.$model->stock_entry_id.'/edit')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StockEntryDetail  $stockEntryDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(StockEntryDetail $stockEntryDetail)
    {
        $stockEntry = StockEntry::find($stockEntryDetail->stock_entry_id);

        if(Auth::user()->id != $stockEntry->user_id) {
            abort(403);
        }
        
        $stockEntryDetail->delete();

        // Redirect ke view headernya langsung
        return redirect('stock_entries/'.$stockEntryDetail->stock_entry_id.'/edit')->with('success','Data berhasil dihapus');
    }
    
    public function getDataItem($item_id,Request $request) {
        
        $item = Item::find($item_id);
        $item->uom_name = $item->default_uom->name;

        //digunakan saat create detail entry
        $ref_code  = $request->get('ref_code');
        $stock_entry_id  = $request->get('stock_entry_id');

        $stock_entries = StockEntry::find($stock_entry_id);

        $item->sed_type = $stock_entries->type;

        //jika outbound/pp, storage yg muncul adalah storage yg mempunyai stok item tersebut.
        if($stock_entries->type == 'outbound'){
            $item->storages = StockEntryDetail::join('stock_entries as se', 'se.id', '=',
                        'stock_entry_details.stock_entry_id')
                        ->join('storages as s','s.id','=','stock_entry_details.storage_id')
                        ->where('stock_entry_details.item_id', $item_id)
                        ->where('se.warehouse_id', $stock_entries->warehouse_id)
                        ->where('se.project_id', $stock_entries->project_id)
                        ->where('se.status', 'Completed')
                        ->where('se.type', 'inbound')
                        ->where('stock_entry_details.ref_code',$ref_code)
                        ->groupBy('stock_entry_details.storage_id')
                        ->selectRaw('s.id,s.code')
                        ->get();
        }

        $item->control_method = $item->control_method;

        return $item;
    }

    public function getControlDate(Request $request) {
        $item_id = $request->get('item_id');
        $ref_code = $request->get('ref_code');
        $storage_id = $request->get('storage_id');
        $project_id = session()->get('current_project')->id;

        $stock_entry_id = $request->get('stock_entry_id');
        $stock_entry_detail_id = $request->get('stock_entry_detail_id');


        $stockEntry = StockEntry::find($stock_entry_id);
        $stockEntryDetail = StockEntryDetail::find($stock_entry_detail_id);

        $getControlDate = StockEntryDetail::join('stock_entries as se', 'se.id', '=',
                                'stock_entry_details.stock_entry_id')
                            ->where('stock_entry_details.item_id',$item_id)
                            ->where('stock_entry_details.ref_code',$ref_code)
                            ->where('stock_entry_details.storage_id',$storage_id)
                            ->where('se.warehouse_id', $stockEntry->warehouse_id)
                            ->where('se.project_id', $project_id)
                            ->where('se.status', 'Completed')
                            ->where('se.type', 'inbound')
                            ->groupBy('stock_entry_details.item_id','stock_entry_details.ref_code','stock_entry_details.storage_id','se.warehouse_id','se.project_id','stock_entry_details.control_date')
                            ->selectRaw('stock_entry_details.control_date,stock_entry_details.ref_code,stock_entry_details.item_id,stock_entry_details.storage_id')
                            ->get();

        //AKITIFIN WAREHOUSE & PROJECT DISINI
        foreach($getControlDate as $controlDate){
            $InboundControlDate = StockEntryDetail::join('stock_entries as se', 'se.id', '=',
                    'stock_entry_details.stock_entry_id')
                    ->where('stock_entry_details.item_id',$item_id)
                    ->where('stock_entry_details.ref_code',$ref_code)
                    ->where('stock_entry_details.storage_id',$storage_id)
                    ->where('stock_entry_details.control_date',$controlDate->control_date)
                    ->where('se.warehouse_id', $stockEntry->warehouse_id)
                    ->where('se.project_id',$project_id)
                    ->where('se.status', 'Completed')
                    ->where('se.type', 'inbound')
                    ->sum('stock_entry_details.qty');

            $OutboundControlDateCompleted = StockEntryDetail::join('stock_entries as se', 'se.id', '=', 'stock_entry_details.stock_entry_id')
            // ->join('stock_deliveries as sd','sd.stock_entry_id','se.id')
            // ->where('sd.status','Completed')
            ->where('se.status', 'Completed')
            ->where('se.type', 'outbound')
            ->where('se.project_id',$project_id)
            ->where('se.warehouse_id', $stockEntry->warehouse_id)
            ->where([
                'stock_entry_details.item_id' => $item_id,
                'stock_entry_details.ref_code' => $ref_code,
                'stock_entry_details.storage_id' => $storage_id,
                'stock_entry_details.control_date' => $controlDate->control_date,
            ])
            ->sum('stock_entry_details.qty');

            //AKTIFIN WAREHOUSE DISINI
            if($stockEntryDetail){

                $OutboundControlDateInCompleted = StockEntryDetail::join('stock_entries as se', 'se.id', '=', 'stock_entry_details.stock_entry_id')
                ->where('se.status', 'Processed')
                ->where('se.type', 'outbound')
                ->where('se.project_id',$project_id)
                ->where('se.warehouse_id', $stockEntry->warehouse_id)
                ->where('stock_entry_details.id','<>',$stockEntryDetail->id)
                ->where([
                    'stock_entry_details.item_id' => $item_id,
                    'stock_entry_details.ref_code' => $ref_code,
                    'stock_entry_details.storage_id' => $storage_id,
                    'stock_entry_details.control_date' => $controlDate->control_date,
                ])
                ->sum('stock_entry_details.qty');
            }
            else{
                $OutboundControlDateInCompleted = StockEntryDetail::join('stock_entries as se', 'se.id', '=', 'stock_entry_details.stock_entry_id')
                ->where('se.status', 'Processed')
                ->where('se.type', 'outbound')
                ->where('se.project_id',$project_id)
                ->where('se.warehouse_id', $stockEntry->warehouse_id)
                ->where([
                    'stock_entry_details.item_id' => $item_id,
                    'stock_entry_details.ref_code' => $ref_code,
                    'stock_entry_details.storage_id' => $storage_id,
                    'stock_entry_details.control_date' => $controlDate->control_date,
                ])
                ->sum('stock_entry_details.qty');
            }

            $fix_outboundControlDate = $OutboundControlDateCompleted+$OutboundControlDateInCompleted;
            $controlDate->qty_available = $InboundControlDate - $fix_outboundControlDate;
            $controlDate->s_code= Storage::find($controlDate->storage_id)->code;
            $controlDate->name= Item::find($controlDate->item_id)->name;
        }

        return $getControlDate;                   
    }
}
