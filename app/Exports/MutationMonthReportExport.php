<?php

namespace App\Exports;

use App\StockEntryDetail;
use App\Warehouse;
use Auth;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class MutationMonthReportExport implements FromView, ShouldAutoSize, WithEvents 
{
    protected $StartDate;
    public function __construct(String $StartDate = null)
    {
        $this->StartDate = $StartDate;
    }

    public function view(): View
    {
        $data['date_from'] = $this->StartDate;
        if($data['date_from']) {

            $changeFormat = Carbon::parse($data['date_from']);
            
            $firstDate = clone $changeFormat->firstOfMonth(); 

            $end_date = clone $changeFormat->endOfMonth(); 

            $data['date_difference'] = $end_date->diffInDays($firstDate, true) + 1;

            $now = Carbon::now();
            // dd($now->year == $firstDate->year ? true: false);
            if($now->month == $firstDate->month && $now->year == $firstDate->year) {
                $data['pembagi'] = $now->day;
            }
            else {
                $data['pembagi'] = $end_date->day;
            }

            $projectId = session()->get('current_project')->id;

            $stock_ins = Warehouse::join('parties', 'parties.id', 'warehouses.branch_id')
            ->join('stock_entries', 'stock_entries.warehouse_id', 'warehouses.id')
            ->join('projects', 'projects.id', 'stock_entries.project_id')
            ->join('stock_entry_details', 'stock_entry_details.stock_entry_id', 'stock_entries.id')
            ->join('storages', 'storages.id', 'stock_entry_details.storage_id')
            ->join('items', 'items.id', 'stock_entry_details.item_id')
            ->join('uoms', 'uoms.id', 'items.default_uom_id')
            ->join('commodities', 'commodities.id', 'items.commodity_id')
            ->where('warehouses.is_active', 1)
            ->where('stock_entries.project_id',  $projectId)
            ->where(DB::raw('Date(stock_entry_details.updated_at)'),'<=', $end_date)
            ->where('stock_entries.status', 'Completed')
            ->where('stock_entries.type', 'inbound');

            if($projectId == '337') {
                $stock_ins->select([
                    'parties.name as branch_name',
                    'warehouses.percentage_buffer',
                    'warehouses.name as warehouse_name',
                    'warehouses.id as warehouse_id',
                    'projects.name as project_name',
                    'items.sku',
                    'items.weight',
                    'warehouses.total_weight as capacity',
                    'items.name as name_sku',
                    'uoms.name as uom_name',
                    'commodities.name as commodity_name',
                    'items.id as item_id',
                    'warehouses.ownership as status_gudang',
                    'stock_entries.project_id',
                    'storages.damage',
                    DB::raw("sum(stock_entry_details.qty) as total_in, CONCAT(stock_entry_details.item_id,'_',warehouses.id,'_', stock_entries.project_id,'_', storages.damage) AS concat_id")
                ]);
            } else {
                $stock_ins->select([
                    'parties.name as branch_name',
                    'warehouses.percentage_buffer',
                    'warehouses.name as warehouse_name',
                    'warehouses.id as warehouse_id',
                    'projects.name as project_name',
                    'commodities.name as commodity_name',
                    'items.sku',
                    'items.weight',
                    'warehouses.total_weight as capacity',
                    'items.name as name_sku',
                    'uoms.name as uom_name',
                    'items.id as item_id',
                    'warehouses.ownership as status_gudang',
                    'stock_entries.project_id',
                    'storages.damage',
                    DB::raw("sum(stock_entry_details.qty) as total_in, CONCAT(warehouses.id,'_',stock_entry_details.item_id ,'_', stock_entries.project_id) AS concat_id")
                ]);
            }

            if(Auth::user()->hasRole('WarehouseManager')) {
                $warehouses = Warehouse::where('branch_id', Auth::user()->branch_id)->pluck('id');
                // dd($warehouses);
                $stock_ins
                    ->whereIn('stock_entries.warehouse_id', $warehouses);
            } else {
                $stock_ins
                ->where('stock_entries.warehouse_id', session()->get('warehouse_id'));
            }
            $stock_ins = $stock_ins->groupBy('concat_id')
            ->get();

            $a = [];

            $stock_out_after_begining = StockEntryDetail::join('stock_entries', 'stock_entries.id', 'stock_entry_details.stock_entry_id');
            if($projectId == '337') {
                $stock_out_after_begining->select([
                    DB::raw("sum(stock_entry_details.qty) as total_out, CONCAT(day(stock_entry_details.updated_at),'_',stock_entry_details.warehouse_id,'_',stock_entry_details.item_id,'_', stock_entries.project_id,'_', storages.damage) AS m_concat_id, day(stock_entry_details.updated_at) as date_out"), 'stock_entry_details.item_id'
                    , 'stock_entry_details.warehouse_id', 'stock_entries.project_id', 'storages.damage','stock_entry_details.storage_id'
                ]);
            }else {
                $stock_out_after_begining->select([
                    DB::raw("sum(stock_entry_details.qty) as total_out, CONCAT(stock_entry_details.warehouse_id,'_',stock_entry_details.item_id,'_', stock_entries.project_id) AS m_concat_id, day(stock_entry_details.updated_at) as date_out"), 'stock_entry_details.item_id'
                    , 'stock_entry_details.warehouse_id', 'stock_entries.project_id', 'storages.damage'
                ]);
            }

            $stock_out_after_begining->where('stock_entries.status', 'Completed')
            ->join('storages', 'storages.id', 'stock_entry_details.storage_id')
            ->where('stock_entries.type', 'outbound')
            ->where('stock_entries.project_id',$projectId);

            if(Auth::user()->hasRole('WarehouseManager')) {
                $stock_out_after_begining
                    ->whereIn('stock_entries.warehouse_id', $warehouses);
            } else {
                $stock_out_after_begining
                ->where('stock_entries.warehouse_id', session()->get('warehouse_id'));
            }
            $stock_out_after_begining = $stock_out_after_begining
            ->whereBetween(DB::raw('Date(stock_entry_details.updated_at)'), [$firstDate, $end_date])
            ->groupBy('m_concat_id')->get();

            $arr_out_after_begining = [];
            foreach ($stock_out_after_begining as $out_after) {
                if($projectId == '337') {
                    $arr_out_after_begining[$out_after->item_id."_".$out_after->warehouse_id."_".$out_after->project_id."_".$out_after->damage][$out_after->date_out]=$out_after->total_out;
                }else{
                    $arr_out_after_begining[$out_after->item_id."_".$out_after->warehouse_id."_".$out_after->project_id][$out_after->date_out]=$out_after->total_out;
                }
            }
    
            $stock_outs = StockEntryDetail::join('stock_entries', 'stock_entries.id', 'stock_entry_details.stock_entry_id')
            ->join('stock_transports', 'stock_transports.id', 'stock_entries.stock_transport_id')
            ->join('stock_advance_notices', 'stock_advance_notices.id', 'stock_transports.advance_notice_id')
            ->join('storages', 'storages.id', 'stock_entry_details.storage_id');
    
            if($projectId == '337') {
                $stock_outs->select([
                    DB::raw("sum(stock_entry_details.qty) as total_out, CONCAT(stock_entry_details.warehouse_id,'_',stock_entry_details.item_id,'_', stock_entries.project_id, '_', storages.damage) AS m_concat_id"), 'stock_entry_details.item_id'
                    , 'stock_entry_details.warehouse_id', 'stock_entries.project_id', 'storages.damage','stock_entry_details.storage_id'
                ]);
            }else {
                $stock_outs->select([
                    DB::raw("sum(stock_entry_details.qty) as total_out, CONCAT(stock_entry_details.warehouse_id,'_',stock_entry_details.item_id,'_', stock_entries.project_id) AS m_concat_id"), 'stock_entry_details.item_id'
                    , 'stock_entry_details.warehouse_id', 'stock_entries.project_id', 'storages.damage'
                ]);
            }
            $stock_outs
            ->where('stock_entries.status', 'Completed')
            ->where('stock_entries.type', 'outbound')
            ->where('stock_advance_notices.advance_notice_activity_id', '<>',20)
            ->where('stock_entries.project_id',  $projectId)
            ->where(DB::raw('Date(stock_entry_details.updated_at)'),'<=', $end_date);
            if(Auth::user()->hasRole('WarehouseManager')) {
                $stock_outs
                    ->whereIn('stock_entries.warehouse_id', $warehouses);
            } else {
                $stock_outs
                ->where('stock_entries.warehouse_id', session()->get('warehouse_id'));
            }
            $stock_outs = $stock_outs->groupBy('m_concat_id')->get();

            $stock_in_after_begining = StockEntryDetail::join('stock_entries', 'stock_entries.id', 'stock_entry_details.stock_entry_id')
            ->join('stock_transports', 'stock_transports.id', 'stock_entries.stock_transport_id')
            ->join('stock_advance_notices', 'stock_advance_notices.id', 'stock_transports.advance_notice_id')
            ->join('storages', 'storages.id', 'stock_entry_details.storage_id');
            if($projectId == '337') {
                $stock_in_after_begining->select([
                    DB::raw("sum(stock_entry_details.qty) as total_in, CONCAT(stock_entry_details.warehouse_id,'_',stock_entry_details.item_id,'_', stock_entries.project_id,'_', storages.damage) AS m_concat_id"), 'stock_entry_details.item_id'
                    , 'stock_entry_details.warehouse_id', 'stock_entries.project_id', 'storages.damage'
                ]);
            }else {
                $stock_in_after_begining->select([
                    DB::raw("sum(stock_entry_details.qty) as total_in, CONCAT(stock_entry_details.warehouse_id,'_',stock_entry_details.item_id,'_', stock_entries.project_id) AS m_concat_id"), 'stock_entry_details.item_id'
                    , 'stock_entry_details.warehouse_id', 'stock_entries.project_id', 'storages.damage'
                ]);
            }

            if(Auth::user()->hasRole('WarehouseManager')) {
                $stock_outs
                    ->whereIn('stock_entries.warehouse_id', $warehouses);
            } else {
                $stock_outs
                ->where('stock_entries.warehouse_id', session()->get('warehouse_id'));
            }
            $stock_in_after_begining = $stock_in_after_begining->where('stock_entries.status', 'Completed')
            ->where('stock_entries.type', 'inbound')
            ->where('stock_advance_notices.advance_notice_activity_id', '<>',20)
            ->where('stock_entries.project_id',  $projectId)
            ->whereBetween(DB::raw('Date(stock_entry_details.updated_at)'),[$firstDate, $end_date])
            ->groupBy('m_concat_id')->get();

            $arr_out = [];
            foreach ($stock_outs as $out) {
                if($projectId == '337') {
                    $arr_out[$out->item_id."_".$out->warehouse_id."_".$out->project_id."_".$out->damage]=$out->total_out;
                } else {
                    $arr_out[$out->item_id."_".$out->warehouse_id."_".$out->project_id]=$out->total_out;
                }
            }
            // dd($arr_out);
            $arr_in_after_begining = [];
            foreach ($stock_in_after_begining as $in_after) {
                if($projectId == '337') {
                    $arr_in_after_begining[$in_after->item_id."_".$in_after->warehouse_id."_".$in_after->project_id."_".$in_after->damage]=$in_after->total_in;
                } else {
                    $arr_in_after_begining[$in_after->item_id."_".$in_after->warehouse_id."_".$in_after->project_id]=$in_after->total_in;
                }
            }

            $arr_by_gudang = [];
            $fix_arr = [];
            // return response()->json($arr_in_after_begining);
            foreach ($stock_ins as $in) {
                // dd($in);
                if($projectId == '337') {
                    if (!empty($arr_out[$in->item_id."_".$in->warehouse_id."_".$in->project_id."_".$in->damage])){
                        // dd('sni');
                        $qty_out = $arr_out[$in->item_id."_".$in->warehouse_id."_".$in->project_id."_".$in->damage];
                    }else{
                        $qty_out = 0;
                    }
                    if (!empty($arr_in_after_begining[$in->item_id."_".$in->warehouse_id."_".$in->project_id."_".$in->damage])){
                        $qty_in_after = $arr_in_after_begining[$in->item_id."_".$in->warehouse_id."_".$in->project_id."_".$in->damage];
                    }else{
                        $qty_in_after = 0;
                    }
                    // dd($qty_in_after);
    
                    if (!empty($arr_out_after_begining[$in->item_id."_".$in->warehouse_id."_".$in->project_id."_".$in->damage])){
                        $qty_out_after = array_sum($arr_out_after_begining[$in->item_id."_".$in->warehouse_id."_".$in->project_id."_".$in->damage]);
                    }else{
                        $qty_out_after = 0;
                    }
                } else {
                    if (!empty($arr_out[$in->item_id."_".$in->warehouse_id."_".$in->project_id])){
                        $qty_out = $arr_out[$in->item_id."_".$in->warehouse_id."_".$in->project_id];
                    }else{
                        $qty_out = 0;
                    }
                    if (!empty($arr_in_after_begining[$in->item_id."_".$in->warehouse_id."_".$in->project_id])){
                        $qty_in_after = $arr_in_after_begining[$in->item_id."_".$in->warehouse_id."_".$in->project_id];
                    }else{
                        $qty_in_after = 0;
                    }
                    // dd($qty_in_after);
    
                    if (!empty($arr_out_after_begining[$in->item_id."_".$in->warehouse_id."_".$in->project_id])){
                        $qty_out_after = array_sum($arr_out_after_begining[$in->item_id."_".$in->warehouse_id."_".$in->project_id]);
                    }else{
                        $qty_out_after = 0;
                    }
                }
                $total_out = $qty_out - $qty_out_after;

                $begining = ($in->total_in - $qty_in_after) - (($qty_out)- $qty_out_after);
                
                $stock = $in->total_in - $qty_out;
               
                $after_begining_in = $qty_in_after;
                $after_begining_out = $qty_out_after;
                $utilitas = round((($stock/$in->capacity)*100),2);
                $kondisi = $in->damage == 1 ? ' (DAMAGE)' : '';
                if($projectId == '337') {
                    $a[] = [
                    'branch' => $in->branch_name,
                    'warehouse' => $in->warehouse_name,
                    'project'   => $in->project_name,
                    'sku'    =>$in->sku,
                    'sku_name' => $in->name_sku . ' '. $kondisi,
                    'uom_name' => $in->uom_name,
                    'commodity_name' => $in->commodity_name,
                    'begining' => $begining,
                    'after_begining_in' => $after_begining_in,
                    'after_begining_out' => $after_begining_out,
                    'stock' => $stock,
                    'item_id' => $in->item_id,
                    'warehouse_id' => $in->warehouse_id,
                    'project_id' => $in->project_id,
                    'damage' => $in->damage,
                    'percentage_buffer' => $in->percentage_buffer,
                    ];
                } else {
                    $a[] = [
                    'branch' => $in->branch_name,
                    'warehouse' => $in->warehouse_name,
                    'project'   => $in->project_name,
                    'sku'    =>$in->sku,
                    'sku_name' => $in->name_sku . ' '. $kondisi,
                    'commodity_name' => $in->commodity_name,
                    'item_id' => $in->item_id,
                    'warehouse_id' => $in->warehouse_id,
                    'project_id' => $in->project_id,
                    'uom_name' => $in->uom_name,
                    'begining' => $begining,
                    'after_begining_in' => $after_begining_in,
                    'after_begining_out' => $after_begining_out,
                    'stock' => $stock,
                    'damage' => 0,
                    'percentage_buffer' => $in->percentage_buffer,

                    ]; 
                }
            }
            // dd($arr_out_after_begining['3848_892_337_0'][12]);
            // $data['out'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']][$i]
            $data['outs'] = $arr_out_after_begining;
            $data['report'] = $a;
            return view('exports.mutation_month')->with(['data'=> $data, 'first_date' => $firstDate, 'end_date' => $end_date]);
        }
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:AV5'; // All headers
                $event->sheet->setAutoFilter('A5:B5');
                // $event->sheet->setAutoSort('A6');

                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);
            },
        ];
    }
}