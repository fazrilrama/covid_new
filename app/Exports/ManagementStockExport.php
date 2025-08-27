<?php

namespace App\Exports;

use App\StockEntryDetail;
use App\Warehouse;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;
use Auth;
use Carbon;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ManagementStockExport implements FromView, ShouldAutoSize, WithEvents 
{
    protected $StartDate,$EndDate, $warehouse_id;
    public function __construct(String  $StartDate = null,String $EndDate = null, String $warehouse_id = null)
    {
        $this->StartDate = $StartDate;
        $this->EndDate = $EndDate;
        $this->warehouse_id = $warehouse_id;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $projectId = session()->get('current_project')->id;
        $from = $this->StartDate;
        $to = $this->EndDate;
        // dd($from, $to);
        
        // begining
        $stock_ins =  Warehouse::join('parties', 'parties.id', 'warehouses.branch_id')
        ->join('stock_entries', 'stock_entries.warehouse_id', 'warehouses.id')
        ->join('stock_transports', 'stock_transports.id', 'stock_entries.stock_transport_id')
        ->join('stock_advance_notices', 'stock_advance_notices.id', 'stock_transports.advance_notice_id')
        ->join('projects', 'projects.id', 'stock_entries.project_id')
        ->join('stock_entry_details', 'stock_entry_details.stock_entry_id', 'stock_entries.id')
        ->join('storages', 'storages.id', 'stock_entry_details.storage_id')
        ->join('items', 'items.id', 'stock_entry_details.item_id')
        ->join('uoms', 'uoms.id', 'items.default_uom_id');
        if($projectId == '337') {
            $stock_ins->select([
                'parties.name as branch_name',
                'warehouses.name as warehouse_name',
                'warehouses.id as warehouse_id',
                'projects.name as project_name',
                'items.sku',
                'items.weight',
                'warehouses.total_weight as capacity',
                'items.name as name_sku',
                'uoms.name as uom_name',
                'items.id as item_id',
                'warehouses.ownership as status_gudang',
                'stock_entries.project_id',
                'storages.damage',
                DB::raw("sum(stock_entry_details.qty) as total_in, CONCAT(stock_entry_details.item_id,'_',warehouses.id,'_', stock_entries.project_id,'_', storages.damage) AS concat_id")
            ]);
        } else {
            $stock_ins->select([
                'parties.name as branch_name',
                'warehouses.name as warehouse_name',
                'warehouses.id as warehouse_id',
                'projects.name as project_name',
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
        $stock_ins->where('warehouses.is_active', 1)
        ->where('stock_advance_notices.advance_notice_activity_id', '<>',20)
        ->where('stock_entries.project_id',  $projectId)
        // ->where(DB::raw('Date(stock_entry_details.created_at)'),'>=' ,$request->date_from)
        // ->where(DB::raw('Date(stock_entry_details.created_at)'),'<=' ,$requet->date_to)
        ->where(DB::raw('Date(stock_entry_details.updated_at)'),'<=', $to)
        ->where('stock_entries.status', 'Completed')
        ->where('stock_entries.type', 'inbound');
        if($this->warehouse_id != 'all') {
            $stock_ins
            ->where('stock_entries.warehouse_id', $this->warehouse_id);
        }
        if(Auth::user()->hasRole('WarehouseManager') && $this->warehouse_id == 'all') {
            $warehouses = Warehouse::where('branch_id', Auth::user()->branch_id)->pluck('id');
            // dd($warehouses);
            $stock_ins
                ->whereIn('stock_entries.warehouse_id', $warehouses);
        }
        $stock_ins = $stock_ins->groupBy('concat_id')
        ->get();
        // dd($stock_ins);
        
        // dd($stock_ins);
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
        $stock_in_after_begining = $stock_in_after_begining->where('stock_entries.status', 'Completed')
        ->where('stock_entries.type', 'inbound')
        ->where('stock_advance_notices.advance_notice_activity_id', '<>',20)
        ->where('stock_entries.project_id',  $projectId)
        ->whereBetween(DB::raw('Date(stock_entry_details.updated_at)'),[$from, $to])
        ->groupBy('m_concat_id')->get();
        
        // dd($stock_in_after_begining);
        
        $stock_out_after_begining = StockEntryDetail::join('stock_entries', 'stock_entries.id', 'stock_entry_details.stock_entry_id')
        ->join('stock_transports', 'stock_transports.id', 'stock_entries.stock_transport_id')
        ->join('stock_advance_notices', 'stock_advance_notices.id', 'stock_transports.advance_notice_id')
        ->join('storages', 'storages.id', 'stock_entry_details.storage_id');

        if($projectId == '337') {
            $stock_out_after_begining->select([
                DB::raw("sum(stock_entry_details.qty) as total_out, CONCAT(stock_entry_details.warehouse_id,'_',stock_entry_details.item_id,'_', stock_entries.project_id,'_', storages.damage) AS m_concat_id"), 'stock_entry_details.item_id'
                , 'stock_entry_details.warehouse_id', 'stock_entries.project_id', 'storages.damage','stock_entry_details.storage_id'
            ]);
        }else {
            $stock_out_after_begining->select([
                DB::raw("sum(stock_entry_details.qty) as total_out, CONCAT(stock_entry_details.warehouse_id,'_',stock_entry_details.item_id,'_', stock_entries.project_id) AS m_concat_id"), 'stock_entry_details.item_id'
                , 'stock_entry_details.warehouse_id', 'stock_entries.project_id', 'storages.damage'
            ]);
        }
        $stock_out_after_begining = $stock_out_after_begining->where('stock_entries.status', 'Completed')
        ->where('stock_entries.type', 'outbound')
        ->where('stock_advance_notices.advance_notice_activity_id', '<>',20)
        ->where('stock_entries.project_id',  $projectId)
        ->whereBetween(DB::raw('Date(stock_entry_details.updated_at)'), [$from, $to])
        ->groupBy('m_concat_id')->get();

        // dd($stock_out_after_begining);

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
        ->where(DB::raw('Date(stock_entry_details.updated_at)'),'<=', $to);

        if($this->warehouse_id != 'all') {
            $stock_outs
            ->where('stock_entry_details.warehouse_id', $this->warehouse_id);
        }
        $stock_outs = $stock_outs->groupBy('m_concat_id')->get();
        // dd($stock_outs);

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
        // dd($arr_in_after_begining);
        $arr_out_after_begining = [];
        foreach ($stock_out_after_begining as $out_after) {
            if($projectId == '337') {
                $arr_out_after_begining[$out_after->item_id."_".$out_after->warehouse_id."_".$out_after->project_id."_".$out_after->damage]=$out_after->total_out;
            }else{
                $arr_out_after_begining[$out_after->item_id."_".$out_after->warehouse_id."_".$out_after->project_id]=$out_after->total_out;
            }
        }
        // dd($arr_out_after_begining);

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
                    $qty_out_after = $arr_out_after_begining[$in->item_id."_".$in->warehouse_id."_".$in->project_id."_".$in->damage];
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
                    $qty_out_after = $arr_out_after_begining[$in->item_id."_".$in->warehouse_id."_".$in->project_id];
                }else{
                    $qty_out_after = 0;
                }
            }
            // dd($qty_out_after);
            $total_out = $qty_out - $qty_out_after;

            $begining = ($in->total_in - $qty_in_after) - (($qty_out)- $qty_out_after);
            
            $stock = $in->total_in - $qty_out;
           
            $after_begining_in = $qty_in_after;
            $after_begining_out = $qty_out_after;
        //   dd($total_out);
            $utilitas = round((($stock/$in->capacity)*100),2);
            // if($stock != 0 && $stock > 0 ) {
            $kondisi = $in->damage == 1 ? ' (DAMAGE)' : '';
            if($projectId == '337') {
                $a = [
                'branch' => $in->branch_name,
                'warehouse' => $in->warehouse_name,
                'project'   => $in->project_name,
                'sku'    =>$in->sku,
                'sku_name' => $in->name_sku . ' '. $kondisi,
                'uom_name' => $in->uom_name,
                'begining' => number_format($begining,0,',',''),
                'after_begining_in' => number_format($after_begining_in,0,',',''),
                'after_begining_out' => number_format($after_begining_out,0,',',''),
                'stock' => number_format(round($stock),2)
                ];
            } else {
                $a = [
                'branch' => $in->branch_name,
                'warehouse' => $in->warehouse_name,
                'project'   => $in->project_name,
                'sku'    =>$in->sku,
                'sku_name' => $in->name_sku . ' '. $kondisi,
                'uom_name' => $in->uom_name,
                'begining' => number_format($begining,0,',',''),
                'after_begining_in' => number_format($after_begining_in,0,',',''),
                'after_begining_out' => number_format($after_begining_out,0,',',''),
                'stock' => number_format(round($stock),2)
                ]; 
            }
            if($begining > 0 || $after_begining_in > 0 || $after_begining_out > 0 || $stock > 0) {
                array_push($fix_arr, $a);
            }
            // }
            //   $fix_arr->push($a);
              
        }
        
        $date = $this->StartDate.' s/d '.$this->EndDate;
        // dd($fix_arr);
        return view('exports.managementstock', [
            'stocks' => $fix_arr,
            'project' => session()->get('current_project')->name,
            'date' => $date
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:K6'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);
            },
        ];
    }
}
