<?php

namespace App\Exports;

use App\StockEntryDetail;
use App\Warehouse;
use App\Item;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;
use Auth;
use Carbon;
use App\User;
use Carbon\CarbonPeriod;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class StockOnLocationReport implements FromView, ShouldAutoSize, WithEvents 
{
    protected $item_id, $warehouse_id, $storage;
    public function __construct(String  $item_id = null, String $warehouse_id = null, String $storage)
    {
        $this->item_id = $item_id;
        $this->warehouse_id = $warehouse_id;
        $this->storage = $storage;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {

        $image = '/public/logo.png';
        $projectId = session()->get('current_project')->id;
        $stock_ins = Warehouse::select([
            'parties.name as branch_name',
            'warehouses.name as warehouse_name',
            'warehouses.id as warehouse_id',
            'projects.name as project_name',
            'items.sku',
            'items.weight',
            'warehouses.total_weight as capacity',
            'items.name as name_sku',
            'stock_entry_details.ref_code',
            'uoms.name as uom_name',
            'items.id as item_id',
            'warehouses.ownership as status_gudang',
            'stock_entries.project_id',
            'storages.code as storage_name',
            'storages.id as storage_id',
            DB::raw("sum(stock_entry_details.qty) as total_in, CONCAT(stock_entry_details.storage_id,'_',stock_entry_details.warehouse_id,'_',stock_entry_details.item_id,'_', stock_entries.project_id) AS concat_id")
        ])
        ->join('parties', 'parties.id', 'warehouses.branch_id')
        ->join('stock_entries', 'stock_entries.warehouse_id', 'warehouses.id')
        ->join('projects', 'projects.id', 'stock_entries.project_id')
        ->join('stock_entry_details', 'stock_entry_details.stock_entry_id', 'stock_entries.id')
        ->join('items', 'items.id', 'stock_entry_details.item_id')
        ->join('uoms', 'uoms.id', 'items.default_uom_id')
        ->leftJoin('storages', 'storages.id', 'stock_entry_details.storage_id')
        ->where('warehouses.is_active', 1)
        ->where('stock_entries.project_id',  $projectId)
        // ->where(DB::raw('Date(stock_entry_details.created_at)'),'>=' ,$request->date_from)
        // ->where(DB::raw('Date(stock_entry_details.created_at)'),'<=' ,$requet->date_to)
        // ->where(DB::raw('Date(stock_entry_details.updated_at) <= NOW()'))
        ->where('stock_entries.status', 'Completed')
        ->where('stock_entries.type', 'inbound');
        if($this->warehouse_id != 'all') {
            $stock_ins
            ->where('stock_entries.warehouse_id', $this->warehouse_id);
        }
        if($this->item_id != 'all') {
            $stock_ins
            ->where('stock_entry_details.item_id', $this->item_id);
        }
        if($this->storage != 'all') {
            $stock_ins
            ->where('stock_entry_details.storage_id', $this->storage);
        }
        $stock_ins = $stock_ins->groupBy('concat_id')
        ->get();

        $stock_outs = StockEntryDetail::select([
            DB::raw("sum(stock_entry_details.qty) as total_out, CONCAT(stock_entry_details.storage_id,'_',stock_entry_details.warehouse_id,'_',stock_entry_details.item_id,'_', stock_entries.project_id) AS m_concat_id"), 'stock_entry_details.item_id'
            , 'stock_entry_details.warehouse_id', 'stock_entries.project_id', 'storages.code','storages.id as storage_id'
        ])
        ->join('stock_entries', 'stock_entries.id', 'stock_entry_details.stock_entry_id')
        ->join('storages', 'storages.id', 'stock_entry_details.storage_id')
        ->where('stock_entries.status', 'Completed')
        ->where('stock_entries.type', 'outbound')
        ->where('stock_entries.project_id',  $projectId);

        if($this->warehouse_id != 'all') {
            $stock_outs
            ->where('stock_entry_details.warehouse_id', $this->warehouse_id);
        }
        if($this->item_id != 'all') {
            $stock_outs
            ->where('stock_entry_details.item_id', $this->item_id);
        }
        if($this->storage != 'all') {
            $stock_outs
            ->where('stock_entry_details.storage_id', $this->storage);
        }
        $stock_outs = $stock_outs->groupBy('m_concat_id')->get();
        // dd($stock_outs);
        // dd($stock_ins);
        $arr_out = [];
        foreach ($stock_outs as $out) {
          $arr_out[$out->storage_id."_".$out->item_id."_".$out->warehouse_id."_".$out->project_id]=[
            'total_out' => $out->total_out
          ];
        }
        $fix_arr = [];
        foreach ($stock_ins as $in) {
            if (!empty($arr_out[$in->storage_id."_".$in->item_id."_".$in->warehouse_id."_".$in->project_id])){
                $qty_out = $arr_out[$in->storage_id."_".$in->item_id."_".$in->warehouse_id."_".$in->project_id]['total_out'];
                
            }else{
                $qty_out = 0;
            }

            $total_out = $qty_out;


            $stock = ($in->total_in) - $qty_out;

        //   dd($total_out);
            $utilitas = round((($stock/$in->capacity)*100),2);
            if($stock != 0 && $stock > 0 ) {
            $a = [
            'branch' => $in->branch_name,
            'warehouse' => $in->warehouse_name,
            'project'   => $in->project_name,
            'sku'    =>$in->sku,
            'storages' => $in->storage_name,
            'packing' => $in->packing,
            'sku_name' => $in->name_sku,
            'ref_code' => $in->ref_code,
            'status_gudang' =>$in->status_gudang,
            'capacity' => number_format(round($in->capacity),2),
            'stock' => number_format($stock,0,',',''),
            'keterangan' => $stock == 0 ? 'Kosong' : 'Masih Tersedia',
            'uom_name' => $in->uom_name
            ];
            
                array_push($fix_arr, $a);
            }
            //   $fix_arr->push($a);
              
        }
        $warehouse = [];
        if($this->warehouse_id != null || $this->warehouse_id != 'all') {
            $warehouse = Warehouse::find($this->warehouse_id);
        }
        return view('exports.stockonlocation', [
            'project' => session()->get('current_project')->name,
            'date' => null,
            'data' => $fix_arr,
            'image' => $image,
            'warehouse' => $warehouse
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:Q7'; // All headers
                // $event->sheet->setBorder('A1:F10', 'thin');
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);
                $event->sheet->getDelegate()->getStyle('A6:Q7')->applyFromArray([
                    'alignment' => array(
                            'horizontal' =>  \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                    ),
                ]);
            },
        ];
    }
}
