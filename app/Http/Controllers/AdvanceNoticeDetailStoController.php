<?php

namespace App\Http\Controllers;

use App\AdvanceNotice;
use App\AdvanceNoticeDetail;
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

class AdvanceNoticeDetailStoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collections = AdvanceNoticeDetail::orderBy('id', 'desc')->get();
        return view('advance_notice_details_sto.index',compact('collections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($advance_notice_id = NULL)
    {
        $projectId = session()->get('current_project')->id;
        $action = route('advance_notice_details_sto.store');
        $method = 'POST';
        //$advance_notices = AdvanceNotice::pluck('code', 'id');
        $advanceNotice = AdvanceNotice::find($advance_notice_id);


        //QUESTION
        // If outbound get existing stock data
        
        if($advanceNotice->type== 'inbound'){
            $items = Item::join('item_projects as ip', 'ip.item_id', '=', 'items.id')
                ->where('ip.project_id', $projectId)
                ->get([
                    'items.id',
                    'items.name',
                    'items.sku',
                ]);
        }


        if ($advanceNotice->type == 'outbound') {

            //get data item berdasar id item tersebut
            $items = Item::whereIn('id', $stock_allocation_item_id)->get(['id','name','sku']);

            //return $items;

            foreach ($items as $item => $value) {
                
                $inbound = 0;

                $outbound_completed = AdvanceNoticeDetail::join('stock_advance_notices as san', 'san.id', '=', 'stock_advance_notice_details.stock_advance_notice_id')
                    ->where('san.status', 'Completed')
                    ->where([
                        'stock_advance_notice_details.item_id' => $value->id,
                        'san.id' => $advanceNotice->id,
                    ])
                    ->sum('stock_advance_notice_details.qty');

                $outbound_incompleted = AdvanceNoticeDetail::join('stock_advance_notices as san', 'san.id', '=', 'stock_advance_notice_details.stock_advance_notice_id')
                    ->where('san.status', 'Processed')
                    ->where([
                        'stock_advance_notice_details.item_id' => $value->id,
                        'san.id' => $advanceNotice->id,
                    ])
                    ->sum('stock_advance_notice_details.qty');

                //return $value->id;
                //return $inbound;

                $value->stock = $inbound - ($outbound_completed + $outbound_incompleted);

                // Kalau ga ada stock take out dr collection
                if($value->stockAvailable==0) {$items->forget($item);continue;}
                
            }
        }
        // dd($items);

        $uoms = Uom::pluck('name', 'id');
        $advanceNoticeDetail = new AdvanceNoticeDetail;
        $advanceNoticeDetail->stock_advance_notice_id = $advance_notice_id; // assign header ID
        return view('advance_notice_details_sto.create',compact('action','method','advanceNoticeDetail','items','uoms','advance_notices', 'advanceNotice'));
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
            'qty_change_and' => 'required|integer',
            'group_references' => 'required'
        ]);



        if ($request->qty_change_and == 0) {
            return redirect()->back()
            ->with('error', 'Jumlah Item Tidak Boleh Kurang dari 1');
        }

        $data = explode(',',$request->get('item_and'));

        $advanceNoticeDetailId = $request->get('stock_advance_notice_id');
        $itemId = $data[0];
        $uomId = $request->get('uom_id');
        $qty = $request->get('qty_change_and');
        $groupReference = $request->get('group_references');
        $weight = $request->get('weight');
        $volume = $request->get('volume');
        $uom_name = Uom::find($uomId)->name;
        $groupReferences = "";

        if ($validator->fails()) {
            return redirect()->back()
                            ->withInput($request->input())
                            ->with('error', $validator->errors()->first())
                            ->with('groupReferences', $groupReferences)
                            ->with('uom_name', $uom_name);
        }

        $advanceNotice = AdvanceNotice::find($advanceNoticeDetailId);

        // jika AON
        if ($advanceNotice->type == 'outbound') {
            
            // check total item
            $item = Item::find($itemId);
            $totalItem = $item->stocks()->where('ref_code',$groupReference)->sum('qty_available');

            //return $qty;
            // dd($totalItem);

            //ini buat ngecek apakah qty_available di stock allocation masih bisa dikurangi qty pertama yg akan diinputkan
            
            if ($qty > $totalItem) {
                return redirect()->back()
                        ->withInput($request->input())
                        ->with('error', 'Jumlah item melebihi jumlah item yang tersedia')
                        ->with('groupReferences', $groupReferences)
                        ->with('uom_name', $uom_name);
            }
            
            $checkItemInside = AdvanceNoticeDetail::where([
                'item_id' => $itemId,
                'stock_advance_notice_id' => $advanceNoticeDetailId,
                'ref_code' => $groupReference
            ])->first();

            //ini buat ngecek apakah qty_available di stock allocation masih bisa dikurangi qty kedua/ketiga/dst yg akan diinputkan

            if (!empty($checkItemInside)) {
                if (($checkItemInside->qty + $qty) > $totalItem) {
                    return redirect()->back()
                            ->withInput($request->input())
                            ->with('error', 'Jumlah item melebihi jumlah item yang tersedia')
                            ->with('groupReferences', $groupReferences)
                            ->with('uom_name', $uom_name);
                }
            }
        }

        // Check apakah sudah ada item yang sama di list item
        $checkItemExist = AdvanceNoticeDetail::where([
                'item_id' => $itemId,
                'stock_advance_notice_id' => $advanceNoticeDetailId,
                'uom_id' => $uomId,
                'ref_code' => $groupReference
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
            $model->item_id = $itemId;
            $model->uom_id = $uomId;
            $model->qty = $qty;
            $model->ref_code = $groupReference;
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
        if($advanceNotice->type == 'inbound'){
            $sub_type = 'ain detail';
        }
        else{
            $sub_type = 'aon detail';
        }

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
        return redirect('stock_transfer_order/'.$advanceNoticeDetailId.'/edit')->with('success', $message);
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
    public function edit($id)
    {
        $advanceNoticeDetail = AdvanceNoticeDetail::find($id);
        $projectId = session()->get('current_project')->id;
        $action = route('advance_notice_details_sto.update', $advanceNoticeDetail->id);
        $method = 'PUT';
        $advanceNotice = AdvanceNotice::find($advanceNoticeDetail->stock_advance_notice_id);

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
            
            //get data item berdasar id item tersebut
            $items = Item::whereIn('id', $stock_allocation_item_id)->get(['id','name','sku']);

            //return $items;

            foreach ($items as $item => $value) {
                
                $inbound = 0;

                $outbound_completed = AdvanceNoticeDetail::join('stock_advance_notices as san', 'san.id', '=', 'stock_advance_notice_details.stock_advance_notice_id')
                    ->where('san.status', 'Completed')
                    ->where([
                        'stock_advance_notice_details.item_id' => $value->id,
                        'san.id' => $advanceNotice->id,
                    ])
                    ->sum('stock_advance_notice_details.qty');

                $outbound_incompleted = AdvanceNoticeDetail::join('stock_advance_notices as san', 'san.id', '=', 'stock_advance_notice_details.stock_advance_notice_id')
                    ->where('san.status', 'Processed')
                    ->where([
                        'stock_advance_notice_details.item_id' => $value->id,
                        'san.id' => $advanceNotice->id,
                    ])
                    ->sum('stock_advance_notice_details.qty');

                //return $value->id;
                //return $inbound;

                $value->stock = $inbound - ($outbound_completed + $outbound_incompleted);

                // Kalau ga ada stock take out dr collection
                if($value->stockAvailable==0) {$items->forget($item);continue;}
                
            }
        }
                
        //return $items;
        $groupReferences = "";

        $uoms = Uom::pluck('name', 'id');
        return view('advance_notice_details_sto.edit',compact('groupReferences','action','method','advanceNoticeDetail', 'advanceNotice','items','uoms'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AdvanceNoticeDetail  $advanceNoticeDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $advanceNoticeDetail = AdvanceNoticeDetail::find($id);
        //Validate
        $request->validate([
            'qty_change_and' => 'required',
            'group_references' => 'required'
        ]);

        if ($request->qty_change_and == 0) {
            return redirect()->back()
            ->with('error', 'Jumlah Item Tidak Boleh Kurang dari 1');
        }



        $data = explode(',',$request->get('item_and'));

        $advanceNoticeDetailId = $advanceNoticeDetail->stock_advance_notice_id;
        $itemId = $data[0];
        $uomId = $request->get('uom_id');
        $qty = $request->get('qty_change_and');
        $groupReference = $request->input('group_references');
        $advanceNotice = AdvanceNotice::find($advanceNoticeDetailId);
        $uom_name = Uom::find($uomId)->name;
        $groupReferences = "";



        // jika AON
        if ($advanceNotice->type == 'outbound') {

            // check total item
            $item = Item::find($itemId);
            $totalItem = $item->stocks()->where('ref_code',$groupReference)->sum('qty_available');
            // dd($qty);
            // If total qty more than total stock return back
            if ($qty>$totalItem) {
                return redirect()->back()
                        ->withInput($request->input())
                        ->with('error', 'Jumlah item melebihi jumlah item yang tersedia')
                        ->with('groupReferences', $groupReferences)
                        ->with('uom_name', $uom_name);
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
        return redirect('stock_transfer_order/'.$model->stock_advance_notice_id.'/edit')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AdvanceNoticeDetail  $advanceNoticeDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdvanceNoticeDetail $advanceNoticeDetail)
    {
        $advanceNoticeDetail->delete();
        // Redirect ke view headernya langsung
        return redirect('stock_transfer_order/'.$advanceNoticeDetail->stock_advance_notice_id.'/edit')->with('success','Data berhasil dihapus');
    }

    // Ini untuk return ref codes untuk proses outbound
    public function getDataItem($id) {
        $data = explode(',',$id);
        
        $item = Item::find($data[0]);
        $item->uom_name = $item->default_uom->name;
        $item->ref_codes = "";

        return $item;
    }
}
