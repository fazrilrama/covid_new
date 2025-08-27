<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as Controller;
use App\StockEntry;
use App\StockEntryDetail;
use App\Transformers\InboundPaList as InboundPaListTransformer;
use App\ItemPutaway;
use App\Transformers\ItemPutawayTransformer;

class InboundPaController extends Controller
{
    public function index()
    {
        $response = StockEntry::leftJoin('warehouses', 'stock_entries.warehouse_id', 'warehouses.id')
                    ->leftJoin('warehouse_officer', 'warehouse_officer.warehouse_id', 'warehouses.id')
                    ->where('warehouse_officer.user_id', auth()->id())
                    ->where('stock_entries.status', request('status', 'Processed'))
                    ->where('stock_entries.type', request('type', 'inbound'))
                    ->select(['stock_entries.id', 'stock_entries.*'])
                    ->paginate(10);

        return fractal($response, new InboundPaListTransformer());
    }

    public function setPa(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric',
            'total_pallet' => 'required|numeric',
            'total_labor' => 'required|numeric',
            'total_forklift' => 'required|numeric',
            'forklift_start_time' => 'required|date_format:Y-m-d H:i',
            'forklift_end_time' => 'required|date_format:Y-m-d H:i',
        ]);

        $total_pallet = $request->get('total_pallet');
        $total_labor = $request->get('total_labor');
        $total_forklift = $request->get('total_forklift');
        $forklift_start_time = $request->get('forklift_start_time');
        $forklift_end_time = $request->get('forklift_end_time');
        /**
         * DEFAULT
         */
        $stock_entry_id = request('id', NULL);

        \DB::beginTransaction();

        try {
            $item = ItemPutaway::where('stock_entry_id', $stock_entry_id);
            if(count($item->get()) > 0) {
                $item->delete();   
            }

            $iw = ItemPutaway::create([
                'stock_entry_id' => $stock_entry_id,
                'total_pallet' => $total_pallet,
                'total_labor' => $total_labor,
                'total_forklift' => $total_forklift,
                'forklift_start_time' => $forklift_start_time,
                'forklift_end_time' => $forklift_end_time,
                'user_id' => auth()->id(),
            ]);

            $st = StockEntry::find($stock_entry_id);
            $st->total_pallet = $total_pallet;
            $st->total_labor = $total_labor;
            $st->total_forklift = $total_forklift;
            $st->forklift_start_time = $forklift_start_time;
            $st->forklift_end_time = $forklift_end_time;
            $st->save();

            \DB::commit();
        } catch (\Throwable $e) {
            \DB::rollback();
            throw $e;
        }

        return fractal($stock_entry_id, new ItemPutawayTransformer());
    }
}
