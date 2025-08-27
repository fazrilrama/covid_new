<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\InternalMovementDetail;
use App\InternalMovement;
use App\Storage;
use App\Warehouse;
use App\StockEntryDetail;
use App\Item;
use DB;
use Auth;

class StockInternalMovementDetailController extends Controller
{
    public function index()
    {

    }

    public function create($id)
    {
        $action = route('stock_internal_movement_details.store');
        $method = 'POST';
        $stock_internal_movement_detail = new InternalMovementDetail;
        $stock_internal = InternalMovement::find($id);
        // dd(session()->get('current_project')->id);
        $storages = Storage::select(['storages.id', 'storages.code'])->join('storage_projects', 'storage_projects.storage_id', 'storages.id')->where('warehouse_id', session()->get('warehouse_id'))->where('storage_projects.project_id', session()->get('current_project')->id)->get();
        return view('stock_internal_movement_details.create')->with([
            'action' => $action,
            'method' => $method,
            'stock_internal_movement_detail' => $stock_internal_movement_detail,
            'stock_internal' => $stock_internal,
            'storages' => $storages
        ]);
    }

    public function store(Request $request)
    {
        $method = 'POST';
        $request->validate([
            'qty_movement' => 'required',
            'dest_storage' => 'required',
            'ref_code' => 'required',
            'item' => 'required',
            'storages' => 'required',
            'internal_movement_id' => 'required',
            'stock' => 'required'
        ]);
        // dd($request);
        $internal_movement = InternalMovement::find($request->internal_movement_id);
        // logikanya gini
        /*
            ketika stocknya lebih maka pindah ke array berikutnya kan?
        */
        $stocks = $this->control_date($request->storages, $request->item, $request->ref_code, $method);
        // dd($stocks);
        $stocks_destination = $this->control_date($request->dest_storage, $request->item, $request->ref_code , $method);
        // dd(array_sum($stocks_destination));
        // dd($stocks);
        // $iter = new \ArrayIterator($a);
        // foreach($a as $key => $aaa) {
        //     $nextValue = $iter->current();
        //     $iter = new \ArrayIterator($a);
        //     $iter->next(); 
        //     $nextKey = $iter->key();
        //     if($nextValue != '4'){
        //         echo 'Total';
        //     }
        // }
        
        $demand = round($request->qty_movement,3);
        $item = Item::find($request->item);
        // dd($item);
        // dd($demand);
        // if($demand > )
        foreach($stocks as $key => $value){
            if ($value <= $demand) {
                $stocks[$key] = 0;
                $supplied[$key] =  round($value,3);
                $demand -=  round($value,3);
            } else {
                // dd($key);
                // dd($demand);
                $stocks[$key] -= $demand;
                $supplied[$key] = round($value,3) - (round($value,3) - round($demand,3));
                $demand = 0;
            }
        }
        $origin_after = array_sum($stocks);
        if($demand > $origin_after){
            return redirect()->back()->with('error', 'Qty lebih besar daripada stock saat ini');
        }
        foreach($supplied as $key => $value) {
            // dd($key);
            if($value != 0) {
                // $origin_after -= round($value,3);
                $checkItemExist = InternalMovementDetail::where([
                    'item_id' => $request->item,
                    'internal_movement_id' => $internal_movement->id,
                    'movement_uom_id' => $item->default_uom_id,
                    'ref_code' => $request->ref_code,
                    'origin_storage_id' => $request->storages,
                    'dest_storage_id' => $request->dest_storage,
                    'control_date' => $key
                ])->first();
                if (!empty($checkItemExist)) {
                    $checkItemExist->movement_qty += round($value,3);
                    $checkItemExist->movement_weight += round($value,3) * round($item->weight,3);
                    $checkItemExist->movement_volume += round($value,3) * round($item->volume,3);
                    $checkItemExist->origin_beginning_qty = round($request->stock,3);
                    $checkItemExist->origin_weight = round($request->stock,3) * round($item->weight,3);
                    $checkItemExist->origin_volume = round($request->stock,3) * round($item->volume,3);
                    $checkItemExist->dest_beginning_qty = array_sum($stocks_destination);
                    $checkItemExist->dest_weight = array_sum($stocks_destination) * round($item->weight,3);
                    $checkItemExist->dest_volume = array_sum($stocks_destination) * round($item->volume,3);
                    $checkItemExist->save();
                } else {
                    $internal_movement->details()->create([
                        'warehouse_id' => session()->get('warehouse_id'),
                        'project_id' => session()->get('current_project')->id,
                        'item_id' => $request->item,
                        'ref_code' => $request->ref_code,
                        'control_date' => $key,
                        'origin_storage_id' => $request->storages,
                        'origin_beginning_qty' => round($request->stock,3),
                        'origin_weight' => round($request->stock,3) * round($item->weight,3),
                        'origin_volume' => round($request->stock,3) * round($item->volume,3),
                        'movement_qty' => round($value,3),
                        'movement_uom_id' => $item->default_uom_id,
                        'movement_weight' => round($value,3) * round($item->weight,3),
                        'movement_volume' => round($value,3) * round($item->volume,3),
                        'dest_storage_id' => $request->dest_storage,
                        'dest_beginning_qty' => array_sum($stocks_destination),
                        'dest_uom_id' => $item->default_uom_id,
                        'dest_begining_qty' => array_sum($stocks_destination),
                        'dest_weight' => array_sum($stocks_destination) * round($item->weight,3),
                        'dest_volume' => array_sum($stocks_destination) * round($item->volume,3),
                    ]);
                }
            }
        }

        return redirect('stock_internal_movements/'.$internal_movement->id.'/edit')->with('success', 'Data berhasil disimpan');
        
        // dd($supplied); 
        // foreach($a )

        // $internal_movement->details()->create([
        //     'warehouse_id' => session()->get('warehouse_id'),
        //     'item_id' => $request->item,
        //     'ref_code' => $request->ref_code,
        //     'origin_storage_id' => $request->storages,
        //     'origin_begining_qty' => $request->sa

        // ]);
    }

    public function detailStorage($storage, Request $request)
    {
        // hitung semua yang ada di storage group by item_id storageid
        $projectId = session()->get('current_project')->id;
        // dd($from, $to);
        // dd($request->warehouse_id);
        // begining
        $stock_ins = Warehouse::select([
            'parties.name as branch_name',
            'warehouses.name as warehouse_name',
            'warehouses.id as warehouse_id',
            'projects.name as project_name',
            'items.sku',
            'items.weight',
            'warehouses.total_weight as capacity',
            'items.name as name_sku',
            'items.sku',
            'uoms.name as uom_name',
            'items.id as item_id',
            'warehouses.ownership as status_gudang',
            'stock_entries.project_id',
            'storages.code as storage_name',
            'storages.id as storage_id',
            'stock_entry_details.ref_code',
            DB::raw("sum(stock_entry_details.qty) as total_in, CONCAT(stock_entry_details.storage_id,'_',stock_entry_details.warehouse_id,'_',stock_entry_details.item_id,'_', stock_entries.project_id, '_', stock_entry_details.ref_code) AS concat_id")
        ])
        ->join('parties', 'parties.id', 'warehouses.branch_id')
        ->join('stock_entries', 'stock_entries.warehouse_id', 'warehouses.id')
        ->join('projects', 'projects.id', 'stock_entries.project_id')
        ->join('stock_entry_details', 'stock_entry_details.stock_entry_id', 'stock_entries.id')
        ->join('items', 'items.id', 'stock_entry_details.item_id')
        ->join('uoms', 'uoms.id', 'items.default_uom_id')
        ->leftJoin('storages', 'storages.id', 'stock_entry_details.storage_id')
        ->where('warehouses.is_active', 1)
        ->where('storages.id', $storage)
        ->where('stock_entries.project_id',  $projectId)
        ->where('stock_entries.warehouse_id', session()->get('warehouse_id'))
        // ->where('storages.id', $storage)
        // ->where(DB::raw('Date(stock_entry_details.created_at)'),'>=' ,$request->date_from)
        // ->where(DB::raw('Date(stock_entry_details.created_at)'),'<=' ,$requet->date_to)
        // ->where(DB::raw('Date(stock_entry_details.updated_at) <= NOW()'))
        ->where('stock_entries.status', 'Completed')
        ->where('stock_entries.type', 'inbound');
        if($request->item_id != null || $request->item_id != '') {
            $stock_ins->where('stock_entry_details.item_id', $request->item_id);
        }
        if($request->ref_code != null || $request->ref_code != '') {
            $stock_ins->where('stock_entry_details.ref_code', $request->ref_code);
        }
        $stock_ins = $stock_ins->groupBy('concat_id')
        ->get();
        // dd($stock_ins);
        $stock_outs = StockEntryDetail::select([
            DB::raw("sum(stock_entry_details.qty) as total_out, CONCAT(stock_entry_details.storage_id,'_',stock_entry_details.warehouse_id,'_',stock_entry_details.item_id,'_', stock_entries.project_id, '_', stock_entry_details.ref_code) AS m_concat_id"), 'stock_entry_details.item_id'
            , 'stock_entry_details.warehouse_id', 'stock_entries.project_id', 'storages.code','storages.id as storage_id', 'stock_entry_details.ref_code'
        ])
        ->join('stock_entries', 'stock_entries.id', 'stock_entry_details.stock_entry_id')
        ->leftJoin('storages', 'storages.id', 'stock_entry_details.storage_id')
        ->where('storages.id', $storage)
        ->where('stock_entries.warehouse_id', session()->get('warehouse_id'))
        ->where('stock_entries.status', 'Completed')
        ->where('stock_entries.type', 'outbound')
        ->where('stock_entries.project_id',  $projectId);
        if($request->item_id != null || $request->item_id != '') {
            $stock_outs->where('stock_entry_details.item_id', $request->item_id);
        }
        if($request->ref_code != null || $request->ref_code != '') {
            $stock_outs->where('stock_entry_details.ref_code', $request->ref_code);
        }
        $stock_outs = $stock_outs->groupBy('m_concat_id')->get();
        // dd($stock_outs);
        // dd($stock_ins);

        $stock_movements = \App\InternalMovementDetail::select(['internal_movement_details.origin_storage_id as storage_id','internal_movement_details.warehouse_id','internal_movement_details.item_id', 'internal_movement_details.project_id', 'internal_movement_details.ref_code', DB::raw("sum(movement_qty) as movement_qty, CONCAT(internal_movement_details.origin_storage_id,'_',internal_movement_details.warehouse_id,'_',internal_movement_details.item_id,'_', internal_movement_details.project_id, '_', internal_movement_details.ref_code) AS m_concat_id")])
        ->join('internal_movements', 'internal_movements.id', 'internal_movement_details.internal_movement_id')
        ->where('internal_movements.status', 'Processed')
        ->where('internal_movements.warehouse_id', session()->get('warehouse_id'))
        ->where('internal_movement_details.origin_storage_id', $storage)
        ->where('internal_movements.project_id',  $projectId);
        if($request->item_id != null || $request->item_id != '') {
            $stock_movements->where('internal_movement_details.item_id', $request->item_id);
        }
        if($request->ref_code != null || $request->ref_code != '') {
            $stock_movements->where('internal_movement_details.ref_code', $request->ref_code);
        }
        $stock_movements = $stock_movements
        ->groupBy('m_concat_id')
        ->get();
       
        $arr_out = [];
        $arr_mv = [];

        foreach ($stock_outs as $out) {
          $arr_out[$out->storage_id."_".$out->item_id."_".$out->warehouse_id."_".$out->project_id."_".$out->ref_code]=$out->total_out;
        }
        foreach ($stock_movements as $mv) {
            $arr_mv[$mv->storage_id."_".$mv->item_id."_".$mv->warehouse_id."_".$mv->project_id."_".$mv->ref_code]=$mv->movement_qty;
          }
        $fix_arr = [];
        // dd($stock_ins);
        $storage_movement = [];
        if($request->item_id != null){
            $storage_movement = Storage::select(['storages.id', 'storages.code'])->join('storage_projects', 'storage_projects.storage_id', 'storages.id')->where('warehouse_id', session()->get('warehouse_id'))->where('storage_projects.project_id', session()->get('current_project')->id)->where('storages.id', '<>',$storage)->get();
        }
        foreach ($stock_ins as $in) {
            if (!empty($arr_out[$in->storage_id."_".$in->item_id."_".$in->warehouse_id."_".$in->project_id."_".$in->ref_code])){
                $qty_out = $arr_out[$in->storage_id."_".$in->item_id."_".$in->warehouse_id."_".$in->project_id."_".$in->ref_code];
            }else{
                $qty_out = 0;
            }
            if (!empty($arr_mv[$in->storage_id."_".$in->item_id."_".$in->warehouse_id."_".$in->project_id."_".$in->ref_code])){
                $qty_movement = $arr_mv[$in->storage_id."_".$in->item_id."_".$in->warehouse_id."_".$in->project_id."_".$in->ref_code];
            }else{
                $qty_movement = 0;
            }


            $total_out = $qty_out;
            $stock = ($in->total_in) - $qty_out - $qty_movement;
        //   dd($total_out);
            // print_r(($in->total_in));
            $utilitas = round((($stock/$in->capacity)*100),2);
            if($stock != 0 && $stock > 0) {
            $a = [
                'sku'    =>$in->sku,
                'storages' => $in->storage_id,
                'ref_code' => $in->ref_code,
                'item' => $in->item_id,
                'sku' => $in->sku,
                'sku_name' => $in->name_sku,
                'stock' => number_format($stock,2, ',', ''),
                'storage' => $storage_movement
            ];
                array_push($fix_arr, $a);
            }
            
              
        }
        // die;
        return $fix_arr;
    }


    function control_date($storage, $item_id, $ref_code, $method)
    {
        $projectId = session()->get('current_project')->id;
        // dd($from, $to);
        // dd($request->warehouse_id);
        // begining
        $stock_ins = Warehouse::select([
            'parties.name as branch_name',
            'warehouses.name as warehouse_name',
            'warehouses.id as warehouse_id',
            'projects.name as project_name',
            'items.sku',
            'items.weight',
            'warehouses.total_weight as capacity',
            'items.name as name_sku',
            'items.sku',
            'uoms.name as uom_name',
            'items.id as item_id',
            'warehouses.ownership as status_gudang',
            'stock_entries.project_id',
            'storages.code as storage_name',
            'storages.id as storage_id',
            'stock_entry_details.ref_code',
            'stock_entry_details.control_date',
            DB::raw("sum(stock_entry_details.qty) as total_in, CONCAT(stock_entry_details.storage_id,'_',stock_entry_details.warehouse_id,'_',stock_entry_details.item_id,'_', stock_entries.project_id, '_', stock_entry_details.ref_code, '_', stock_entry_details.control_date) AS concat_id")
        ])
        ->join('parties', 'parties.id', 'warehouses.branch_id')
        ->join('stock_entries', 'stock_entries.warehouse_id', 'warehouses.id')
        ->join('projects', 'projects.id', 'stock_entries.project_id')
        ->join('stock_entry_details', 'stock_entry_details.stock_entry_id', 'stock_entries.id')
        ->join('items', 'items.id', 'stock_entry_details.item_id')
        ->join('uoms', 'uoms.id', 'items.default_uom_id')
        ->leftJoin('storages', 'storages.id', 'stock_entry_details.storage_id')
        ->where('warehouses.is_active', 1)
        ->where('storages.id', $storage)
        ->where('stock_entries.project_id',  $projectId)
        ->where('stock_entries.warehouse_id', session()->get('warehouse_id'))
        // ->where('storages.id', $storage)
        // ->where(DB::raw('Date(stock_entry_details.created_at)'),'>=' ,$request->date_from)
        // ->where(DB::raw('Date(stock_entry_details.created_at)'),'<=' ,$requet->date_to)
        // ->where(DB::raw('Date(stock_entry_details.updated_at) <= NOW()'))
        ->where('stock_entries.status', 'Completed')
        ->where('stock_entries.type', 'inbound')
        ->where('stock_entry_details.item_id', $item_id)
        ->where('stock_entry_details.ref_code', $ref_code);
        $stock_ins = $stock_ins->groupBy('concat_id')
        ->orderBy('stock_entry_details.control_date', 'asc')
        ->get();
        // dd($stock_ins);
        $stock_outs = StockEntryDetail::select([
            DB::raw("sum(stock_entry_details.qty) as total_out, CONCAT(stock_entry_details.storage_id,'_',stock_entry_details.warehouse_id,'_',stock_entry_details.item_id,'_', stock_entries.project_id, '_', stock_entry_details.ref_code, '_', stock_entry_details.control_date) AS m_concat_id"), 'stock_entry_details.item_id'
            , 'stock_entry_details.warehouse_id', 'stock_entries.project_id', 'storages.code','storages.id as storage_id', 'stock_entry_details.ref_code','stock_entry_details.control_date'
        ])
        ->join('stock_entries', 'stock_entries.id', 'stock_entry_details.stock_entry_id')
        ->leftJoin('storages', 'storages.id', 'stock_entry_details.storage_id')
        ->where('storages.id', $storage)
        ->where('stock_entries.warehouse_id', session()->get('warehouse_id'))
        ->where('stock_entries.status', 'Completed')
        ->where('stock_entries.type', 'outbound')
        ->where('stock_entries.project_id',  $projectId)
        ->where('stock_entry_details.item_id', $item_id)
        ->where('stock_entry_details.ref_code', $ref_code);
       
        $stock_outs = $stock_outs->groupBy('m_concat_id')
        ->orderBy('stock_entry_details.control_date', 'asc')
        ->get();
        // dd($stock_outs);
        // dd($stock_ins);
        $arr_out = [];
        foreach ($stock_outs as $out) {
          $arr_out[$out->storage_id."_".$out->item_id."_".$out->warehouse_id."_".$out->project_id."_".$out->ref_code."_".$out->control_date]=$out->total_out;
        }
        $fix_arr = [];
        if($method == 'POST') {
            $stock_movements = \App\InternalMovementDetail::select(['internal_movement_details.origin_storage_id as storage_id','internal_movement_details.control_date', 'internal_movement_details.warehouse_id','internal_movement_details.item_id', 'internal_movement_details.project_id', 'internal_movement_details.ref_code', DB::raw("sum(movement_qty) as movement_qty, CONCAT(internal_movement_details.origin_storage_id,'_',internal_movement_details.warehouse_id,'_',internal_movement_details.item_id,'_', internal_movement_details.project_id, '_', internal_movement_details.ref_code, '_', internal_movement_details.control_date) AS m_concat_id")])
            ->join('internal_movements', 'internal_movements.id', 'internal_movement_details.internal_movement_id')
            ->where('internal_movements.status', 'Processed')
            ->where('internal_movements.warehouse_id', session()->get('warehouse_id'))
            ->where('internal_movement_details.origin_storage_id', $storage)
            ->where('internal_movements.project_id',  $projectId)
            ->where('internal_movement_details.item_id', $item_id)
            ->where('internal_movement_details.ref_code', $ref_code)
            ->groupBy('m_concat_id')
            ->get();
        } else {
            $stock_movements = [];
        }
        

        $arr_mv = [];
        foreach ($stock_movements as $mv) {
            $arr_mv[$mv->storage_id."_".$mv->item_id."_".$mv->warehouse_id."_".$mv->project_id."_".$mv->ref_code."_".$mv->control_date]=$mv->movement_qty;
          }
        // dd($arr_mv);
        $a = [];
        foreach ($stock_ins as $in) {
            if (!empty($arr_out[$in->storage_id."_".$in->item_id."_".$in->warehouse_id."_".$in->project_id."_".$in->ref_code."_".$in->control_date])){
                $qty_out = $arr_out[$in->storage_id."_".$in->item_id."_".$in->warehouse_id."_".$in->project_id."_".$in->ref_code."_".$in->control_date];
            }else{
                $qty_out = 0;
            }

            if (!empty($arr_mv[$in->storage_id."_".$in->item_id."_".$in->warehouse_id."_".$in->project_id."_".$in->ref_code."_".$in->control_date])){
                $qty_movement = $arr_mv[$in->storage_id."_".$in->item_id."_".$in->warehouse_id."_".$in->project_id."_".$in->ref_code."_".$in->control_date];
            }else{
                $qty_movement = 0;
            }

            $total_out = $qty_out;
            $stock = ($in->total_in) - $qty_out - $qty_movement;
        //   dd($total_out);
            $utilitas = round((($stock/$in->capacity)*100),2);
            // if($stock != 0 && $stock > 0) {
            $a[$in->control_date] = $stock;
            // array_push($fix_arr, $a);
            // }
        }
        return $a;
    }

    public function edit(InternalMovementDetail $stock_internal_movement_detail, Request $request)
    {
        // dd($stock_internal_movement_detail);
        $action = route('stock_internal_movement_details.update', $stock_internal_movement_detail->id);
        $method = 'PUT';
        $stock_internal = InternalMovement::find($stock_internal_movement_detail->internal_movement_id);
        // dd($stock_internal_movement_detail);

        $stock_detial = InternalMovementDetail::where('item_id', $stock_internal_movement_detail->item_id)
        ->where('ref_code', $stock_internal_movement_detail->ref_code)
        ->where('internal_movement_id', $stock_internal_movement_detail->internal_movement_id)
        ->where('origin_storage_id', $stock_internal_movement_detail->origin_storage_id)
        ->where('dest_storage_id', $stock_internal_movement_detail->dest_storage_id)
        ->sum('movement_qty');
        
        $origin_storage = $this->detailStorage($stock_internal_movement_detail->origin_storage_id, $request);
        // dd($origin_storage);
        $request->request->add([
            'item_id' => $stock_internal_movement_detail->item_id,     
        ]); 
        $request->request->add([
            'ref_code' => $stock_internal_movement_detail->ref_code,     
        ]); 
        $detail = $this->detailStorage($stock_internal_movement_detail->origin_storage_id, $request);
        
        // dd($detail);
        // dd($detail[0]['storage']);
        // dd(session()->get('current_project')->id);
        $storages = Storage::select(['storages.id', 'storages.code'])->join('storage_projects', 'storage_projects.storage_id', 'storages.id')->where('warehouse_id', session()->get('warehouse_id'))->where('storage_projects.project_id', session()->get('current_project')->id)->get();
        $storages_exeption = Storage::select(['storages.id', 'storages.code'])->join('storage_projects', 'storage_projects.storage_id', 'storages.id')->where('warehouse_id', session()->get('warehouse_id'))->where('storage_projects.project_id', session()->get('current_project')->id)->where('storages.id', '<>', $stock_internal_movement_detail->origin_storage_id)->get();

        return view('stock_internal_movement_details.edit')->with([
            'action' => $action,
            'method' => $method,
            'stock_internal_movement_detail' => $stock_internal_movement_detail,
            'stock_internal' => $stock_internal,
            'storages' => $storages,
            'origin_storage' => $origin_storage,
            'detail' => $detail,
            'stock_detial' => $stock_detial,
            'storages_exeption' => $storages_exeption
        ]);
    }

    public function update(InternalMovementDetail $stock_internal_movement_detail, Request $request)
    {
        $method = 'PUT';
        $request->validate([
            'qty_movement' => 'required',
            'dest_storage' => 'required',
            'ref_code' => 'required',
            'item' => 'required',
            'storages' => 'required',
            'internal_movement_id' => 'required',
            'stock' => 'required'
        ]);
        $internal_movement = InternalMovement::find($request->internal_movement_id);
        // logikanya gini
        /*
        ketika stocknya lebih maka pindah ke array berikutnya kan?
        */
        
        DB::beginTransaction();

        $stock_detial = InternalMovementDetail::where('item_id', $stock_internal_movement_detail->item_id)
        ->where('ref_code', $stock_internal_movement_detail->ref_code)
        ->where('internal_movement_id', $stock_internal_movement_detail->internal_movement_id)
        ->where('origin_storage_id', $stock_internal_movement_detail->origin_storage_id)
        ->where('dest_storage_id', $stock_internal_movement_detail->dest_storage_id)
        ->delete();
        
        $stocks = $this->control_date($request->storages, $request->item, $request->ref_code, $method);
        // dd($stocks);
        
        $stocks_destination = $this->control_date($request->dest_storage, $request->item, $request->ref_code, $method);
        // dd(array_sum($stocks_destination));
        // dd($stocks);
        // $iter = new \ArrayIterator($a);
        // foreach($a as $key => $aaa) {
        //     $nextValue = $iter->current();
        //     $iter = new \ArrayIterator($a);
        //     $iter->next(); 
        //     $nextKey = $iter->key();
        //     if($nextValue != '4'){
        //         echo 'Total';
        //     }
        // }
        // dd($stocks);
        $demand = round($request->qty_movement,3);
        $item = Item::find($request->item);
        $origin_after = array_sum($stocks);
        if($demand > $origin_after){
            DB::rollback();
            return redirect()->back()->with('error', 'Qty lebih besar daripada stock saat ini');
        }
        
        foreach($stocks as $key => $value){
            if ($value <= $demand) {
                $stocks[$key] = 0;
                $supplied[$key] =  round($value,3);
                $demand -=  round($value,3);
            } else {
                // dd($key);
                // dd($demand);
                $stocks[$key] -= $demand;
                $supplied[$key] = round($value,3) - (round($value,3) - round($demand,3));
                $demand = 0;
            }
        }
       
        // dd($origin_after);
        
        // dd($origin_after);
        foreach($supplied as $key => $value) {
            // dd($key);
            if($value != 0) {
                // $origin_after -= round($value,3);
                $checkItemExist = InternalMovementDetail::where([
                    'item_id' => $request->item,
                    'internal_movement_id' => $internal_movement->id,
                    'movement_uom_id' => $item->default_uom_id,
                    'ref_code' => $request->ref_code,
                    'origin_storage_id' => $request->storages,
                    'dest_storage_id' => $request->dest_storage,
                    'control_date' => $key
                ])->first();
                if (!empty($checkItemExist)) {
                    $checkItemExist->movement_qty += round($value,3);
                    $checkItemExist->movement_weight += round($value,3) * round($item->weight,3);
                    $checkItemExist->movement_volume += round($value,3) * round($item->volume,3);
                    $checkItemExist->origin_beginning_qty = round($request->stock,3);
                    $checkItemExist->origin_weight = round($request->stock,3) * round($item->weight,3);
                    $checkItemExist->origin_volume = round($request->stock,3) * round($item->volume,3);
                    $checkItemExist->dest_beginning_qty = array_sum($stocks_destination);
                    $checkItemExist->dest_weight = array_sum($stocks_destination) * round($item->weight,3);
                    $checkItemExist->dest_volume = array_sum($stocks_destination) * round($item->volume,3);
                    $checkItemExist->save();
                } else {
                    $internal_movement->details()->create([
                        'warehouse_id' => session()->get('warehouse_id'),
                        'project_id' => session()->get('current_project')->id,
                        'item_id' => $request->item,
                        'ref_code' => $request->ref_code,
                        'control_date' => $key,
                        'origin_storage_id' => $request->storages,
                        'origin_beginning_qty' => round($request->stock,3),
                        'origin_weight' => round($request->stock,3) * round($item->weight,3),
                        'origin_volume' => round($request->stock,3) * round($item->volume,3),
                        'movement_qty' => round($value,3),
                        'movement_uom_id' => $item->default_uom_id,
                        'movement_weight' => round($value,3) * round($item->weight,3),
                        'movement_volume' => round($value,3) * round($item->volume,3),
                        'dest_storage_id' => $request->dest_storage,
                        'dest_beginning_qty' => array_sum($stocks_destination),
                        'dest_uom_id' => $item->default_uom_id,
                        'dest_begining_qty' => array_sum($stocks_destination),
                        'dest_weight' => array_sum($stocks_destination) * round($item->weight,3),
                        'dest_volume' => array_sum($stocks_destination) * round($item->volume,3),
                    ]);
                }
            }
        }
        DB::commit();
        return redirect('stock_internal_movements/'.$internal_movement->id.'/edit')->with('success', 'Data berhasil disimpan');
    }

    public function destroy(InternalMovementDetail $stock_internal_movement_detail)
    {
        $stock_detial = InternalMovementDetail::where('item_id', $stock_internal_movement_detail->item_id)
        ->where('ref_code', $stock_internal_movement_detail->ref_code)
        ->where('internal_movement_id', $stock_internal_movement_detail->internal_movement_id)
        ->where('origin_storage_id', $stock_internal_movement_detail->origin_storage_id)
        ->where('dest_storage_id', $stock_internal_movement_detail->dest_storage_id)
        ->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');

    }
}
