<?php

namespace App\Exports;

use App\StockTransportDetail;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class StockTransportDetailExport implements FromView, ShouldAutoSize, WithEvents 
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
        $type = 'inbound';
        // dd(session()->all());
        $projectId = session()->get('current_project')->id;

        // dd(session()->get('current_project')->name);
        // dd(StockTransportDetail::all());
        $stocks = StockTransportDetail::select([
            'stock_transports.id',
            'stock_transport_details.updated_at as control_date',
            'parties.name as parties_name',
            'uoms.name as uom',
            'items.name as item_name',
            'stock_transports.status',
            'stock_transports.police_number',
            'stock_transports.driver_name',
            'stock_transport_details.qty',
            'stock_transports.do_number',
            'items.sku',
            'stock_transports.pic',
            'stock_transports.ref_code'
        ])->join('stock_transports', 'stock_transports.id', 'stock_transport_details.stock_transport_id')
        ->join('items', 'items.id', 'stock_transport_details.item_id')
        ->join('uoms', 'items.default_uom_id', 'uoms.id');
        if($projectId != '337') {
            $stocks->leftJoin('parties', 'parties.id', 'stock_transports.owner');
        } else {
            $stocks->join('parties', 'parties.id', 'stock_transports.shipper_id')
            ->where('warehouse_id', session()->get('warehouse_id'));
        }
       $stocks->where('stock_transports.project_id', $projectId)
        ->where('stock_transports.id', '<>', 347007)
        ->where('type', $type);
        if($this->warehouse_id != 'all' && $this->warehouse_id != null) {
            $stocks->where('stock_transports.warehouse_id', $this->warehouse_id);
        }
        if($this->item_id != 'all' && $this->item_id != null) {
            $stocks
            ->where('stock_transport_details.item_id', $this->item_id);
        }
        $date = '';
        if($this->StartDate != null && $this->EndDate != null) {
            $stocks->whereBetween(DB::raw('Date(stock_transport_details.updated_at)'), [$this->StartDate, $this->EndDate]);
            $date = $this->StartDate.'s/d'.$this->EndDate;
        }
        $stocks = $stocks->get();
        

        return view('exports.detail', [
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
