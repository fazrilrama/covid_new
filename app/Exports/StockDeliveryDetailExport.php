<?php

namespace App\Exports;

use App\StockDeliveryDetail;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class StockDeliveryDetailExport implements FromView, ShouldAutoSize, WithEvents 
{
    protected $StartDate,$EndDate, $item_id, $warehouse_id;
    public function __construct(String  $StartDate = null,String $EndDate = null, String $item_id = null, String $warehouse_id = null)
    {
        $this->StartDate = $StartDate;
        $this->EndDate = $EndDate;
        $this->item_id = $item_id;
        $this->warehouse_id = $warehouse_id;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $projectId = session()->get('current_project')->id;
        // dd(session()->get('current_project')->name);
        // dd(StockTransportDetail::all());
        $stocks = StockDeliveryDetail::select([
            'stock_deliveries.id',
            'stock_delivery_details.updated_at as control_date',
            'parties.name as parties_name',
            'uoms.name as uom',
            'items.name as item_name',
            'stock_deliveries.status',
            'stock_deliveries.police_number',
            'stock_deliveries.driver_name',
            'stock_delivery_details.qty',
            'stock_deliveries.do_number',
            'stock_delivery_details.ref_code as item_ref_code',
            'items.sku',
            'stock_deliveries.pic',
            'stock_deliveries.ref_code',
            'a.name as consignee_name'
        ])->join('stock_deliveries', 'stock_deliveries.id', 'stock_delivery_details.stock_delivery_id')
        ->join('stock_entries', 'stock_entries.id', 'stock_deliveries.stock_entry_id')
        ->join('items', 'items.id', 'stock_delivery_details.item_id')
        ->join('parties as a', 'a.id', 'stock_deliveries.consignee_id')
        ->join('uoms', 'items.default_uom_id', 'uoms.id')
        ->leftJoin('parties', 'parties.id', 'stock_deliveries.owner')
        ->where('stock_deliveries.project_id', $projectId);
        
        if($this->warehouse_id != 'all' && $this->warehouse_id != null) {
            $stocks->where('stock_entries.warehouse_id', $this->warehouse_id);
        }
        if($this->item_id != 'all' && $this->item_id != null) {
            $stocks
            ->where('stock_delivery_details.item_id', $this->item_id);
        }
        $date = '';
        if($this->StartDate != null && $this->EndDate != null) {
            $stocks->whereBetween(DB::raw('Date(stock_delivery_details.updated_at)'), [$this->StartDate, $this->EndDate]);
            $date = $this->StartDate.' s/d '.$this->EndDate;
        }
        $stocks = $stocks->get();
        // dd($stocks);
        
        return view('exports.detail_outbound', [
            'stocks' => $stocks,
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
