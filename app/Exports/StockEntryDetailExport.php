<?php

namespace App\Exports;

use App\StockEntryDetail;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class StockEntryDetailExport implements FromView, ShouldAutoSize, WithEvents 
{
    protected $type;
    public function __construct(String $type)
    {
        $this->type = $type;

    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $type = $this->type;
        $projectId = session()->get('current_project')->id;
        // dd(session()->get('current_project')->name);
        // dd(StockTransportDetail::all());
        $stocks = StockEntryDetail::select([
            'stock_entries.id',
            'stock_entries.code',
            'stock_entry_details.updated_at as control_date',
            'uoms.name as uom',
            'items.name as item_name',
            'stock_entries.status',
            'stock_entry_details.qty',
            'items.sku',
            'stock_entry_details.ref_code',
            'stock_entry_details.created_at',
        ])->join('stock_entries', 'stock_entries.id', 'stock_entry_details.stock_entry_id')
        ->join('items', 'items.id', 'stock_entry_details.item_id')
        ->join('uoms', 'items.default_uom_id', 'uoms.id')
        ->where('stock_entries.project_id', $projectId)
        ->where('type', $type);
    
        $stocks = $stocks->get();
        $date = '';

        // dd($stocks);
        return view('exports.detail_entries', [
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
