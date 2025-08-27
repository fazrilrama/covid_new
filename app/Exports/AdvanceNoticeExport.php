<?php

namespace App\Exports;

use App\AdvanceNotice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;


class AdvanceNoticeExport implements FromCollection, WithHeadingRow, WithHeadings, ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */

    //use Exportable;
    protected $itemDetailRepository;
    protected $type;

    public function __construct($collections)
    {
        $this->collections = $collections; 

        foreach($this->collections as $collection){
            unset($collection->id); 
            unset($collection->user_id); 
            unset($collection->item_employee_name); 
            unset($collection->status);
        }
    }

    public function collection()
    {
        //return $type = $this->type;
        //return AdvanceNotice::where('type', $this->type)->get(['code','type']);
        //return AdvanceNotice::get(['id','first_name','email','created_at','updated_at']);
        //return $this->collections;
        return collect($this->collections);
    }

    public function headings(): array
    {
        return [
            'Code',
            'Storage',
            'Origin',
            'Destination',
            'ETD',
            'ETA',
            'Outstanding',
            'Status',
            // 'Receiving',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:I1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);
            },
        ];
    }
}
