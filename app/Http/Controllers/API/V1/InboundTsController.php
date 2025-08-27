<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as Controller;
use App\StockTransport;
use App\StockTransportDetail;
use App\Transformers\InboundTsList as InboundTsListTransformer;
use App\ItemWeigher;
use App\Transformers\ItemWeigherTransformer;

class InboundTsController extends Controller
{
    public function index()
    {
        $response = StockTransport::leftJoin('warehouses', 'stock_transports.warehouse_id', 'warehouses.id')
                    ->leftJoin('warehouse_officer', 'warehouse_officer.warehouse_id', 'warehouses.id')
                    ->where('warehouse_officer.user_id', auth()->id())
                    ->where('stock_transports.status', request('status', 'Processed'))
                    ->where('stock_transports.type', request('type', 'inbound'))
                    ->select(['stock_transports.id', 'stock_transports.*'])
                    ->paginate(10);

        return fractal($response, new InboundTsListTransformer());
    }

    public function setTs(Request $request)
    {
        $request->validate([
            'qty' => 'required|numeric',
            'content_weight' => 'required|numeric',
            'empty_weight' => 'required|numeric',
        ]);

        $qty = request('qty', 0);
        $content_weight = request('content_weight', 0);
        $empty_weight = request('empty_weight', 0);
        $volume = request('volume', 0);
        /**
         * DEFAULT
         */
        $stock_transport_id = request('stock_transport_id', NULL);
        $stock_transport_detail_id = request('stock_transport_detail_id', NULL);
        $uom_id = request('uom_id', NULL);

        \DB::beginTransaction();

        try {
            $item = ItemWeigher::where('stock_transport_detail_id', $stock_transport_detail_id);
            if(count($item->get()) > 0) {
                $item->delete();   
            }

            $iw = ItemWeigher::create([
                'stock_transport_id' => $stock_transport_id,
                'stock_transport_detail_id' => $stock_transport_detail_id,
                'qty' => $qty,
                'content_weight' => $content_weight,
                'empty_weight' => $empty_weight,
                'volume' => $volume,
                'uom_id' => $uom_id,
                'user_id' => auth()->id(),
            ]);

            $st = StockTransportDetail::find($stock_transport_detail_id);
            $st->qty = (float)($iw->qty);
            $st->weight = (float)($iw->content_weight - $iw->empty_weight);
            $st->volume = $st->plan_volume;
            $st->save();

            $iw->volume = $st->plan_volume;
            $iw->save();

            \DB::commit();
        } catch (\Throwable $e) {
            \DB::rollback();
            throw $e;
        }

        return fractal($stock_transport_id, new ItemWeigherTransformer());
    }

    public function setPutAway(Request $request)
    {
        $request->validate([
            'stock_entry_id' => 'required|numeric',
            'total_pallet' => 'required|numeric',
            'total_labor' => 'required|numeric',
            'total_forklift' => 'required|numeric',
            'forklift_start_time' => 'required|date_format:Y-m-d H:i',
            'forklift_end_time' => 'required|date_format:Y-m-d H:i',
        ]);

        $stock_entry_id = $request->get('stock_entry_id');
        $total_pallet = $request->get('total_pallet');
        $total_labor = $request->get('total_labor');
        $total_forklift = $request->get('total_forklift');
        $forklift_start_time = $request->get('forklift_start_time');
        $forklift_end_time = $request->get('forklift_end_time');

        return $request->all();
    }
}
