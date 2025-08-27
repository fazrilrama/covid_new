<?php

namespace App\Exports;

use App\StockTransportDetail;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;
use App\StockDelivery;
use App\Project;    
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class GoodReceiveReportExport implements FromView, ShouldAutoSize, WithEvents 
{
    protected $project_id;
    public function __construct(String  $project_id = null)
    {
        $this->project_id = $project_id;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        // $projectId = session()->get('current_project')->id;
        // dd(session()->get('current_project')->name);
        // dd(StockTransportDetail::all());
        $deliveries = StockDelivery::select(['stock_deliveries.stock_entry_id','stock_deliveries.code', 'stock_deliveries.id', 'stock_deliveries.ref_code', 'consignee_id', 'origin_id', 'destination_id', 'stock_deliveries.status', 'stock_deliveries.photo', 'stock_deliveries.received_by', 'stock_deliveries.police_number', 'stock_deliveries.driver_name', 'stock_deliveries.pic', 'stock_deliveries.date_received as updated_at', 'stock_deliveries.shipper_id', 'stock_deliveries.created_at', 'stock_deliveries.eta', 'stock_delivery_details.qty', 'items.name as item_name', 'uoms.name as uom_name'])
        ->join('stock_delivery_details', 'stock_delivery_details.stock_delivery_id', 'stock_deliveries.id')
        ->join('items', 'items.id', 'stock_delivery_details.item_id')
        ->join('uoms', 'items.default_uom_id', 'uoms.id')
        ->join('stock_entries', 'stock_entries.id', 'stock_deliveries.stock_entry_id')
        ->whereIn('warehouse_id', ['892', '1385'])->with([
            'stock_entry.warehouse', 'origin', 'destination', 'consignee', 'shipper'
        ]);     
        if($this->project_id != 'all') {
            $deliveries->where('stock_deliveries.project_id', $this->project_id);
        }
        $project = [];
        $deliveries = $deliveries->get();
        // dd($deliveries);
        if($this->project_id != 'all') {
        $project = Project::find($this->project_id);
        }
        // dd($stocks);
        return view('exports.detail_gi', [
            'deliveries' => $deliveries,
            'project' => $project
        ]);
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:M7'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray([
                    'font' => [
                        'bold' => true
                    ],
                    'alignment' => array(
                        'horizontal' =>  \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                    )
                ]);
                $a = $event->sheet->getDelegate()->getHighestRow();
                $event->sheet->getStyle('J7:L'.$a)->getAlignment()->setWrapText(true);
                // dd($a);
                $event->sheet->getDelegate()->getStyle('A7:M'.$a)->applyFromArray([
                    'alignment' => array(
                            'horizontal' =>  \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                    ),
                ]);
                // $event->sheet->getDelegate()->getHighestRow()->applyFromArray([
                //     'alignment' => array(
                //         'horizontal' =>  \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                //     ) 
                // ]);
            },
        ];
    }
}
