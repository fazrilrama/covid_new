<?php

namespace App\Http\Controllers;

use App\City;
use App\Region;
use App\StockDelivery;
use Carbon;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('transporters.create');   
        // return view('')
    }

    public function DOCompleted()
    {
        $deliveries = StockDelivery::select('stock_deliveries.code', 'stock_deliveries.id', 'stock_deliveries.ref_code')
        // ->where('warehouse_id', '')
        ->join('stock_entries', 'stock_entries.id', 'stock_deliveries.stock_entry_id')
        ->join('stock_transports', 'stock_transports.id', 'stock_entries.stock_transport_id')
        ->join('stock_advance_notices', 'stock_advance_notices.id', 'stock_transports.advance_notice_id')
        ->whereNotIn('stock_advance_notices.advance_notice_activity_id',['10', '7', '13'])
        ->whereIn('stock_entries.warehouse_id', ['892', '1385', '888'])
        ->whereIn('stock_entries.project_id', ['331', '332', '334', '336'])
        ->where('stock_deliveries.status', 'Completed')->get();
        return response()->json($deliveries);
    }

    public function stockDeliveryDetail(StockDelivery $stock_delivery)
    {
        // dd($id);
        $stock_delivery->load([
            'origin'=>function($query){
                $query->select(['id', 'name']);
            }, 'destination'=>function($query){
                $query->select(['id', 'name']);
            }, 'details'=> function($query){
                $query->selectRaw('FORMAT(qty,0) as qty,ref_code, item_id, stock_delivery_id');   
            }, 'details.item'=>function($query){
                $query->select(['id','name', 'sku']);
            }, 'transport_type', 'shipper', 'consignee'
            
        ]);
        return response()->json($stock_delivery);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\City  $city
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // dd($request->date_arrival);
        if($request->id_transaction != null) {
            $model = StockDelivery::find($request->id_transaction);
            // $a = Carbon::parse($model->created_at);
            // $b = Carbon::CreateFromFormat('d/m/Y g:i A',$request->date_arrival)->format('Y-m-d');
            // dd($b->diffInHours($a));
            // dd($a->min($b));
            // dd($b->diffInDays($a));
            if($request->hasFile('photo')) {
                $model->photo = $request->photo->store('deliveries', 'public');
            }
            if($request->hasFile('photo_unboxing')) {
                $model->photo_box = $request->photo_unboxing->store('deliveries', 'public');
            }
            if($request->hasFile('photo_unboxing')) {
                $model->photo_signature = $request->photo_signature->store('deliveries', 'public');
            }
            $model->status = 'Received';
            
            $model->received_by = $request->penerima;
            if($request->date_arrival != null) {
                $model->date_received = Carbon::CreateFromFormat('m/d/Y g:i A',$request->date_arrival)->format('Y-m-d h:m:s');
                // if($model->date_received < $model->created_at){
                //     return redirect()->route('deliveries.index')->with('error', 'Mohon Tanggal terima tidak boleh lebih kecil daripada tanggal GI dibuat');
                // }
            }
            $model->save();
            return redirect()->route('deliveries.index')->with('success', 'Data berhasil '. $model->code .' disimpan');
        } else {
            return redirect()->route('deliveries.index')->with('error', 'Mohon Lengkapi Data');
        }
        // $model = $stock_delivery;
        // $model->code = $request->get('code');
        // $model->name = $request->get('name');
        // $model->province_id = $request->get('region_id');
        // $model->save();
        // return redirect()->route('transporters.create')->with('success', 'Data berhasil disimpan');
    }
}
