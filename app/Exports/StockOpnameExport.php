<?php

namespace App\Exports;

use App\StockOpname;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;
use Auth;
use Carbon;
use Carbon\CarbonPeriod;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use \Maatwebsite\Excel\Sheet;

class StockOpnameExport implements FromView,WithEvents 
{
    protected $id;
    public function __construct(String  $id = null)
    {
        $this->id = $id;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        // dd($fix_arr);
        // dd($id);
        $stock_opname = StockOpname::find($this->id);
        // dd($stock_opname);

        return view('stock_opnames.export', [
            'stock_opname' => $stock_opname
        ]);
    }

    public function registerEvents(): array
    {
        return [
            
            // AfterSheet::class    => function(AfterSheet $event) {
            //     // Macro
            //     $event->sheet
            //     ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
                
            //     // Or via magic __call
            //     $event->sheet
            //     ->getPageSetup()
            //     ->setScale(91);
            // },

            
            AfterSheet::class    => function(AfterSheet $event) {

                Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
                    $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
                });
                
                $event->sheet->getDelegate()->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
                $event->sheet->getDelegate()->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

                $cellRange = 'B3:U3'; // All headers
                // $event->sheet->setBorder('A1:F10', 'thin');
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => '15',
                        'name' =>  'Tahoma',
                    ]
                ]);

                $cellContent = 'B4:U300';

                $event->sheet->getDelegate()->getStyle($cellContent)->applyFromArray([
                    'font' => [
                        'name' =>  'Calibri',
                        'bold' => false,
                    ]
                ]);
                $event->sheet->getDelegate()->getStyle('B3:U3')->applyFromArray([
                    'alignment' => array(
                            'horizontal' =>  \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                    ),
                ]);
           },
        ];
    }
}
