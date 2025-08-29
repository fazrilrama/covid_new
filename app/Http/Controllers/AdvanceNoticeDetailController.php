<?php

namespace App\Http\Controllers;

use App\AdvanceNotice;
use App\AdvanceNoticeDetail;
use App\StockTransportDetail;
use App\DataLog;
use App\StockEntry;
use App\StockEntryDetail;
use App\Item;
use App\Storage;
use App\Warehouse;
use App\ItemProjects;
use App\Uom;
use App\Project;
use DB;
use Auth;
use Illuminate\Http\Request;
use Validator;

class AdvanceNoticeDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collections = AdvanceNoticeDetail::orderBy('id', 'desc')->get();
        return view('advance_notice_details.index',compact('collections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($advance_notice_id = NULL)
    {
        $action = route('advance_notice_details.store');
        $method = 'POST';
        //$advance_notices = AdvanceNotice::pluck('code', 'id');
        $advanceNotice = AdvanceNotice::find($advance_notice_id);
        // if() {
        //     dd('true');
        // } else {
        //     dd('false');
        // }

        // if((Auth::user()->hasRole('WarehouseSupervisor') && $advanceNotice->project_id != '337')) {
        //     if(Auth::user()->id != $advanceNotice->user_id || $advanceNotice->status == 'Completed') {
        //         abort(403);
        //     }
        // }

        // dd('test');


        //QUESTION
        // If outbound get existing stock data
        //item yg muncul adalah item yg ada di projek saat ini
        if($advanceNotice->type == 'inbound'){
            $items = Item::join('item_projects as ip', 'ip.item_id', '=', 'items.id')
                ->where('ip.project_id', $advanceNotice->project_id)
                ->get([
                    'items.id',
                    'items.name',
                    'items.sku',
                ]);
        }


        if ($advanceNotice->type == 'outbound') {
            $storages_id = Storage::join('warehouses as w','w.id','=','storages.warehouse_id')
                            ->join('storage_projects as sp','sp.storage_id','=','storages.id')
                            ->where('w.id', $advanceNotice->warehouse_id)
                            ->where('w.branch_id', $advanceNotice->shipper_id)
                            ->where('sp.project_id', $advanceNotice->project_id)
                            ->pluck('storages.id');

            $items = StockEntryDetail::join('stock_entries as se', 'se.id', '=', 'stock_entry_details.stock_entry_id')
                ->where('se.project_id', $advanceNotice->project_id)
                ->where('se.warehouse_id', $advanceNotice->warehouse_id)
                ->where('se.type', 'inbound')
                ->where('se.status', 'Completed')
                ->whereIn('stock_entry_details.storage_id', $storages_id)
                ->orderBy('stock_entry_details.item_id')
                ->groupBy('stock_entry_details.item_id','stock_entry_details.ref_code')
                ->selectRaw('stock_entry_details.item_id,stock_entry_details.ref_code,
                    stock_entry_details.storage_id,stock_entry_details.id,
                    sum(stock_entry_details.qty) as qty,se.warehouse_id,se.project_id')
                ->get();      

            //menghitung stock yg tersisa di allocation jika dikurangi dengan aon yg lain
            foreach($items as $item){
                $inbound = $item->qty;

                //hitung stock yg tergantung di aon closed
                $inbound_closed = AdvanceNoticeDetail::join('stock_advance_notices as san', 'san.id', '=', 'stock_advance_notice_details.stock_advance_notice_id')
                    ->where('san.status', 'Closed')
                    ->where('san.type', 'outbound')
                    ->where('san.warehouse_id', $advanceNotice->warehouse_id)
                    ->where('san.project_id', $advanceNotice->project_id)
                    ->where([
                        'stock_advance_notice_details.item_id' => $item->item_id,
                        'stock_advance_notice_details.ref_code' => $item->ref_code,
                    ])
                    ->sum('stock_advance_notice_details.qty');
                    
                //hanya dp yg dihitung karna ini aon
                $outbound_closed = StockTransportDetail::join('stock_transports as st', 'st.id', '=', 'stock_transport_details.stock_transport_id')
                ->join('stock_advance_notices as san', 'san.id', '=', 'st.advance_notice_id')
                ->where('st.status', 'Completed')
                ->where('st.type', 'outbound')
                ->where('san.status', 'Closed')
                ->where('san.type', 'outbound')
                ->where('san.warehouse_id', $advanceNotice->warehouse_id)
                ->where('san.project_id', $advanceNotice->project_id)
                ->where([
                    'stock_transport_details.item_id' => $item->item_id,
                    'stock_transport_details.ref_code' => $item->ref_code,
                ])
                ->sum('stock_transport_details.plan_qty');

                $stock_closed = $inbound_closed - $outbound_closed;

                //inbound ditambahkan dengan sisa stock aon yg telah diclosed.
                $inbound += $stock_closed;

                $item->qty = $inbound;

                //stock dikurangi aon dari luar
                $outbound_outside = AdvanceNoticeDetail::join('stock_advance_notices as an', 'an.id', '=', 'stock_advance_notice_details.stock_advance_notice_id')
                    ->where('an.project_id',  $advanceNotice->project_id)
                    ->where('an.warehouse_id', $advanceNotice->warehouse_id)
                    ->where('an.type', 'outbound')
                    ->where('an.id','<>', $advanceNotice->id)
                    ->where('stock_advance_notice_details.item_id',$item->item_id)
                    ->where('stock_advance_notice_details.ref_code',$item->ref_code)
                    ->sum('stock_advance_notice_details.qty');      

                //stock dikurangi aon sendiri
                $outbound_inside = AdvanceNoticeDetail::join('stock_advance_notices as an', 'an.id', '=', 'stock_advance_notice_details.stock_advance_notice_id')
                    ->where('an.project_id', $advanceNotice->project_id)
                    ->where('an.warehouse_id', $advanceNotice->warehouse_id)
                    ->where('an.id', $advanceNotice->id)
                    ->where('stock_advance_notice_details.item_id',$item->item_id)
                    ->where('stock_advance_notice_details.ref_code',$item->ref_code)
                    ->sum('stock_advance_notice_details.qty');      

                $item->stock = $inbound - ($outbound_outside + $outbound_inside);
                $item->out = $outbound_outside + $outbound_inside;
                $item->in = $inbound;
            }

            $items = $items->filter(function ($item) {
                return $item->stock > 0;
            });

        }
        // dd($items);

        //$uoms = Uom::pluck('name', 'id');
        $advanceNoticeDetail = new AdvanceNoticeDetail;
        $advanceNoticeDetail->stock_advance_notice_id = $advance_notice_id; // assign header ID

        $advance_notices = $advanceNotice;
        return view('advance_notice_details.create',compact('action','method','advanceNoticeDetail','items','advance_notices', 'advanceNotice'));
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
        $validator = Validator::make($request->all(), [
            'qty_change_and' => 'required|numeric',
            'group_references' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                            ->withInput($request->input())
                            ->with('error', $validator->errors()->first());
        }

        if ($request->qty_change_and == 0) {
            return redirect()->back()
            ->with('error', 'Jumlah Item Tidak Boleh Kurang dari 1');
        }

        $advanceNoticeDetailId = $request->get('stock_advance_notice_id');

        $advanceNotice = AdvanceNotice::find($advanceNoticeDetailId);
        // if(Auth::user()->hasRole('WarehouseSupervisor') && $advanceNotice->project_id != '337') {
        //     if(Auth::user()->id != $advanceNotice->user_id || $advanceNotice->status == 'Completed') {
        //         abort(403);
        //     }
        // }

        $item_id = $request->get('item_and');

        $stock = $request->get('stock');

        $uomId = $request->get('uom_id');
        $qty = $request->get('qty_change_and');
        $ref_code = $request->get('group_references');

        $weight = $request->get('weight');
        $volume = $request->get('volume');
        $uom_name = Uom::find($uomId)->name;

        // jika AON
        if ($advanceNotice->type == 'outbound') {
            
            //stock dikurangi aon dari luar
            $outbound_outside = AdvanceNoticeDetail::join('stock_advance_notices as an', 'an.id', '=', 'stock_advance_notice_details.stock_advance_notice_id')
                ->where('an.project_id', $advanceNotice->project_id)
                ->where('an.warehouse_id', $advanceNotice->warehouse_id)
                ->where('an.type', 'outbound')
                ->where('an.id','<>', $advanceNotice->id)
                ->where('stock_advance_notice_details.item_id',$item_id)
                ->where('stock_advance_notice_details.ref_code',$ref_code)
                ->sum('stock_advance_notice_details.qty');      

            //stock dikurangi aon sendiri
            $outbound_inside = AdvanceNoticeDetail::join('stock_advance_notices as an', 'an.id', '=', 'stock_advance_notice_details.stock_advance_notice_id')
                ->where('an.project_id', $advanceNotice->project_id)
                ->where('an.warehouse_id', $advanceNotice->warehouse_id)
                ->where('an.id', $advanceNotice->id)
                ->where('stock_advance_notice_details.item_id',$item_id)
                ->where('stock_advance_notice_details.ref_code',$ref_code)
                ->sum('stock_advance_notice_details.qty');      

            $inbound = $stock - ($outbound_outside + $outbound_inside);
            // dd($ref_code);

            //return $inbound;

            if ($qty > $inbound) {

                return redirect()->back()
                        ->withInput($request->input())
                        ->with('error', 'Jumlah stock item yang diinput melebihi jumlah stock item yang ada digudang');
            }
        }
        
        // Check apakah sudah ada item yang sama di list item
        $checkItemExist = AdvanceNoticeDetail::where([
                'item_id' => $item_id,
                'stock_advance_notice_id' => $advanceNoticeDetailId,
                'uom_id' => $uomId,
                'ref_code' => $ref_code
            ])->first();

        $message = '';

        // Kalau ada tambahan ke existing item
        if (!empty($checkItemExist)) {
            $checkItemExist->qty += $qty; 
            $checkItemExist->weight += $weight; 
            $checkItemExist->volume += $volume; 
            $checkItemExist->save();
            $message = 'Jumlah item berhasil ditambahkan';
        }else{
            // Buat baru jika belum ada
            $model = new AdvanceNoticeDetail;
            $model->stock_advance_notice_id = $advanceNoticeDetailId;
            $model->item_id = $item_id;
            $model->uom_id = $uomId;
            $model->qty = $qty;
            $model->ref_code = $ref_code;
            $model->weight = $weight;
            $model->volume = $volume;
            $model->save();
            $message = 'Item berhasil ditambah';
        }

        // Jika berhasil menambah barang ganti status header
        if ($advanceNotice->status != 'Completed') {
            $advanceNotice->status = 'Processed';
            $advanceNotice->save();

            try {
                activity()
                    ->performedOn($advanceNotice)
                    ->causedBy(Auth::user())
                    ->withProperties($advanceNotice)
                    ->log('Processed ' . ($advanceNotice->type == 'inbound' ? 'AIN' : 'AON') );
            } catch (\Exception $e) {
                
            }
        }

        //create data log
        // if($advanceNotice->type == 'inbound'){
        //     $sub_type = 'ain detail';
        // }
        // else{
        //     $sub_type = 'aon detail';
        // }

        // $data_log = array(
        //     'user_id' => Auth::user()->id,
        //     'type' => $advanceNotice->type,
        //     'sub_type' => $sub_type,
        //     'record_id' => $advanceNotice->id,
        //     'status' => 'proccess',
        // );

        // $input = array_except($data_log, '_token');
        // $DataLog = DataLog::create($input);

        // Redirect ke view headernya langsung
        return redirect('advance_notices/'.$advanceNoticeDetailId.'/edit')->with('success', $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AdvanceNoticeDetail  $advanceNoticeDetail
     * @return \Illuminate\Http\Response
     */
    public function show(AdvanceNoticeDetail $advanceNoticeDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AdvanceNoticeDetail  $advanceNoticeDetail
     * @return \Illuminate\Http\Response
     */
    public function edit(AdvanceNoticeDetail $advanceNoticeDetail)
    {
        $projectId = session()->get('current_project')->id;
        $action = route('advance_notice_details.update', $advanceNoticeDetail->id);
        $method = 'PUT';
        $advanceNotice = AdvanceNotice::find($advanceNoticeDetail->stock_advance_notice_id);

        $stock_allocation_item_id = null;

        // if(Auth::user()->id != $advanceNotice->user_id || $advanceNotice->status == 'Completed' || (Auth::user()->hasRole('WarehouseSupervisor') && $advanceNotice->project_id != '337')) {
        //     abort(403);
        // }

        $old_qty = $advanceNoticeDetail->qty;

        //tampilkan list item yg ada projeknya saja
        if($advanceNotice->type== 'inbound'){
            $items = Item::join('item_projects as ip', 'ip.item_id', '=', 'items.id')
                ->where('ip.project_id', $projectId)
                ->get([
                    'items.id',
                    'items.name',
                    'items.sku',
                ]);
        }

        // If outbound get existing stock data
        if ($advanceNotice->type == 'outbound') {
            $storages_id = Storage::join('warehouses as w','w.id','=','storages.warehouse_id')
                            ->join('storage_projects as sp','sp.storage_id','=','storages.id')
                            ->where('w.id', $advanceNotice->warehouse_id)
                            ->where('w.branch_id', $advanceNotice->shipper_id)
                            ->where('sp.project_id', $advanceNotice->project_id)
                            ->pluck('storages.id');

            $items = StockEntryDetail::join('stock_entries as se', 'se.id', '=', 'stock_entry_details.stock_entry_id')
                ->where('se.project_id', $advanceNotice->project_id)
                ->where('se.warehouse_id', $advanceNotice->warehouse_id)
                ->where('se.type', 'inbound')
                ->where('se.status', 'Completed')
                ->whereIn('stock_entry_details.storage_id', $storages_id)
                ->orderBy('stock_entry_details.item_id')
                ->groupBy('stock_entry_details.item_id','stock_entry_details.ref_code')
                ->selectRaw('stock_entry_details.item_id,stock_entry_details.ref_code,
                    stock_entry_details.storage_id,stock_entry_details.id,
                    sum(stock_entry_details.qty) as qty,se.warehouse_id,se.project_id')
                ->get();      

            //menghitung stock yg tersisa di allocation jika dikurangi dengan aon yg lain dan detail sendiri
            foreach($items as $item){
                $inbound = $item->qty;

                //hitung stock yg tergantung di aon closed
                $inbound_closed = AdvanceNoticeDetail::join('stock_advance_notices as san', 'san.id', '=', 'stock_advance_notice_details.stock_advance_notice_id')
                    ->where('san.status', 'Closed')
                    ->where('san.type', 'outbound')
                    ->where('san.warehouse_id', $advanceNotice->warehouse_id)
                    ->where('san.project_id', $advanceNotice->project_id)
                    ->where([
                        'stock_advance_notice_details.item_id' => $item->item_id,
                        'stock_advance_notice_details.ref_code' => $item->ref_code,
                    ])
                    ->sum('stock_advance_notice_details.qty');
                    
                //hanya dp yg dihitung karna ini aon
                $outbound_closed = StockTransportDetail::join('stock_transports as st', 'st.id', '=', 'stock_transport_details.stock_transport_id')
                ->join('stock_advance_notices as san', 'san.id', '=', 'st.advance_notice_id')
                ->where('st.status', 'Completed')
                ->where('st.type', 'outbound')
                ->where('san.status', 'Closed')
                ->where('san.type', 'outbound')
                ->where('san.warehouse_id', $advanceNotice->warehouse_id)
                ->where('san.project_id', $advanceNotice->project_id)
                ->where([
                    'stock_transport_details.item_id' => $item->item_id,
                    'stock_transport_details.ref_code' => $item->ref_code,
                ])
                ->sum('stock_transport_details.plan_qty');

                $stock_closed = $inbound_closed - $outbound_closed;

                //inbound ditambahkan dengan sisa stock aon yg telah diclosed.
                $inbound += $stock_closed;

                $item->qty = $inbound;

                //stock dikurangi aon dari luar
                $outbound_outside = AdvanceNoticeDetail::join('stock_advance_notices as an', 'an.id', '=', 'stock_advance_notice_details.stock_advance_notice_id')
                    ->where('an.project_id',  $advanceNotice->project_id)
                    ->where('an.warehouse_id', $advanceNotice->warehouse_id)
                    ->where('an.type', 'outbound')
                    ->where('an.id','<>', $advanceNotice->id)
                    ->where('stock_advance_notice_details.item_id',$item->item_id)
                    ->where('stock_advance_notice_details.ref_code',$item->ref_code)
                    ->sum('stock_advance_notice_details.qty');      

                //stock dikurangi aon sendiri kecuali item detail itu sendiri
                $outbound_inside = AdvanceNoticeDetail::join('stock_advance_notices as an', 'an.id', '=', 'stock_advance_notice_details.stock_advance_notice_id')
                    ->where('an.project_id', $advanceNotice->project_id)
                    ->where('an.warehouse_id', $advanceNotice->warehouse_id)
                    ->where('an.id', $advanceNotice->id)
                    ->where('stock_advance_notice_details.id','<>',$advanceNoticeDetail->id)
                    ->where('stock_advance_notice_details.item_id',$item->item_id)
                    ->where('stock_advance_notice_details.ref_code',$item->ref_code)
                    ->sum('stock_advance_notice_details.qty');      

                $item->stock = $inbound - ($outbound_outside + $outbound_inside);
            }

            $items = $items->filter(function ($item) {
                return $item->stock > 0;
            });

        }
                
        //return $items;
        return view('advance_notice_details.edit',compact('action','method','advanceNoticeDetail', 'advanceNotice','items','stock_allocation_item_id','old_qty'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AdvanceNoticeDetail  $advanceNoticeDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AdvanceNoticeDetail $advanceNoticeDetail)
    {
        //Validate
        $request->validate([
            'qty_change_and' => 'required',
            'group_references' => 'required'
        ]);

        if ($request->qty_change_and == 0) {
            return redirect()->back()
            ->with('error', 'Jumlah Item Tidak Boleh Kurang dari 1');
        }

        $advanceNoticeDetailId = $advanceNoticeDetail->stock_advance_notice_id;
        $itemId = $request->get('item_and');
        $uomId = $request->get('uom_id');

        $stock = $request->get('stock');
        $qty = $request->get('qty_change_and');

        $ref_code = $request->input('group_references');
        $advanceNotice = AdvanceNotice::find($advanceNoticeDetailId);
        $uom_name = Uom::find($uomId)->name;

        // if(Auth::user()->id != $advanceNotice->user_id || $advanceNotice->status == 'Completed' || (Auth::user()->hasRole('WarehouseSupervisor') && $advanceNotice->project_id != '337')) {
        //     abort(403);
        // }

        // jika AON
        if ($advanceNotice->type == 'outbound') {

            //stock dikurangi aon dari luar
            $outbound_outside = AdvanceNoticeDetail::join('stock_advance_notices as an', 'an.id', '=', 'stock_advance_notice_details.stock_advance_notice_id')
                ->where('an.project_id', $advanceNotice->project_id)
                ->where('an.warehouse_id', $advanceNotice->warehouse_id)
                ->where('an.type', 'outbound')
                ->where('an.id','<>', $advanceNotice->id)
                ->where('stock_advance_notice_details.item_id',$itemId)
                ->where('stock_advance_notice_details.ref_code',$ref_code)
                ->sum('stock_advance_notice_details.qty');      

            //stock dikurangi aon sendiri kecuali item detail itu sendiri
            $outbound_inside = AdvanceNoticeDetail::join('stock_advance_notices as an', 'an.id', '=', 'stock_advance_notice_details.stock_advance_notice_id')
                ->where('an.project_id', $advanceNotice->project_id)
                ->where('an.warehouse_id', $advanceNotice->warehouse_id)
                ->where('an.id', $advanceNotice->id)
                ->where('stock_advance_notice_details.id','<>',$advanceNoticeDetail->id)
                ->where('stock_advance_notice_details.item_id',$itemId)
                ->where('stock_advance_notice_details.ref_code',$ref_code)
                ->sum('stock_advance_notice_details.qty');      

            $outstanding = $stock - ($outbound_outside + $outbound_inside);

            if ($qty > $outstanding) {
                return redirect()->back()
                        ->withInput($request->input())
                        ->with('error', 'Jumlah item melebihi jumlah item yang tersedia');
            }      
        }

        $model = $advanceNoticeDetail;
        $model->stock_advance_notice_id = $request->get('stock_advance_notice_id');
        $model->item_id = $itemId;
        $model->uom_id = $request->get('uom_id');
        $model->qty = $qty;
        $model->weight = $request->get('weight');
        $model->volume = $request->get('volume');
        $model->ref_code = $request->get('group_references');
        $model->save();

        // Redirect ke view headernya langsung
        return redirect('advance_notices/'.$model->stock_advance_notice_id.'/edit')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AdvanceNoticeDetail  $advanceNoticeDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdvanceNoticeDetail $advanceNoticeDetail)
    {
        $advanceNotice = AdvanceNotice::find($advanceNoticeDetail->stock_advance_notice_id);

        // if(Auth::user()->id != $advanceNotice->user_id || (Auth::user()->hasRole('WarehouseSupervisor') && $advanceNotice->project_id != '337')) {
        //     abort(403);
        // }

        $advanceNoticeDetail->delete();
        // Redirect ke view headernya langsung
        return redirect('advance_notices/'.$advanceNoticeDetail->stock_advance_notice_id.'/edit')->with('success','Data berhasil dihapus');
    }

    // Ini untuk return ref codes untuk proses outbound
    public function getDataItem($id,Request $request) {
        
        $item = Item::find($id);
        $item->uom_name = $item->default_uom->name;

        return $item;
    }
}
