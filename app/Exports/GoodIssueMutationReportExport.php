<?php

namespace App\Exports;

use App\StockTransportDetail;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;
use Carbon\CarbonPeriod;
use App\StockDelivery;
use App\Project;    
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class GoodIssueMutationReportExport implements FromView, ShouldAutoSize, WithEvents 
{
    protected $project_id,$StartDate,$EndDate;
    public function __construct(String  $project_id = null, String  $StartDate = null,String $EndDate = null)
    {
        $this->project_id = $project_id;
        $this->StartDate = $StartDate;
        $this->EndDate = $EndDate;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
            $project = [];
            if($this->project_id != 'all') 
            {
                $project = Project::find($this->project_id);
            }
            $collections = [];
            // sisa pengiriman awal didapatkan melalui nomor gi yang belum received tp sudah complete di gudang lebih kecil dari hari yang di filter
            // pengiiriman dibuat adalah ketika udah complete di gudang
            // pengiriman sampai adalah ketika sudah received
            // pengiriman akhir ketika dia di tanggal itu tp 
            $begining_delivery = StockDelivery::where(DB::raw('Date(stock_deliveries.created_at)'), '<' ,$this->StartDate)
            ->join('stock_entries', 'stock_entries.id', 'stock_deliveries.stock_entry_id')
            ->whereIn('warehouse_id', ['892', '1385']);
                    if($this->project_id != 'all') 
                    {
                        $begining_delivery
                        ->where('stock_deliveries.project_id', $this->project_id); 
                    }
                    $begining_delivery->whereIn('stock_deliveries.status',['Received', 'Completed']);
                // ->where('stock_deliveries.date_received', '<>', null);    
            $begining_delivery = $begining_delivery->select([
                        'stock_deliveries.id as stock_delivery_id',
                    ])
                    ->count('stock_deliveries.id');
            $receiving_item = StockDelivery::where(DB::raw('Date(stock_deliveries.date_received)'), '<' ,$this->StartDate)
            ->join('stock_entries', 'stock_entries.id', 'stock_deliveries.stock_entry_id')
            ->whereIn('warehouse_id', ['892', '1385']);
                    if($this->project_id != 'all') 
                    {
                        $receiving_item
                        ->where('stock_deliveries.project_id', $this->project_id); 
                    }
                    $receiving_item->whereIn('stock_deliveries.status',['Received']);
                // ->where('stock_deliveries.date_received', '<>', null);    
            $receiving_item = $receiving_item->select([
                        'stock_deliveries.id as stock_delivery_id',
                    ])
                    ->count('stock_deliveries.id');
        
            $beginings = $begining_delivery - $receiving_item;
            
            // dd($beginings);
            $periods = CarbonPeriod::create($this->StartDate, $this->EndDate);
            // dd(count($periods) - 19);
            $i = 0;
            $countPeriods = count($periods);
            // dd($collections);
            foreach ($periods as $period) {
                $dateItem = $period->format('Y-m-d');
                $receiving_item = StockDelivery::where(DB::raw('Date(stock_deliveries.created_at)'), $dateItem)
                            ->join('stock_entries', 'stock_entries.id', 'stock_deliveries.stock_entry_id')
            ->whereIn('warehouse_id', ['892', '1385']);

                            // ->where('stock_deliveries.project_id', $this->project_id)
                            if($this->project_id != 'all') 
                            {
                                $receiving_item
                                ->where('stock_deliveries.project_id', $this->project_id); 
                            }
                            $receiving_item->whereIn('stock_deliveries.status',['Completed', 'Received']);
                            $receiving_item = $receiving_item->select([
                                DB::raw('count(stock_deliveries.id) as qty_receiving'),
                                DB::raw('DATE(stock_deliveries.created_at) as transaction_date'),
                            ])
                            ->groupBy('transaction_date')
                            ->orderBy('transaction_date', 'ASC')
                            ->first();
                // dd($receiving_item);

                $qty_issuing = StockDelivery::where(DB::raw('Date(stock_deliveries.date_received)'), $dateItem)
                ->join('stock_entries', 'stock_entries.id', 'stock_deliveries.stock_entry_id')
                ->whereIn('warehouse_id', ['892', '1385']);
                                if($this->project_id != 'all') 
                                {
                                    $qty_issuing
                                    ->where('stock_deliveries.project_id', $this->project_id); 
                                }
                                $qty_issuing
                                ->where('stock_deliveries.status','Received')
                                ->where('stock_deliveries.date_received', '<>', null);
                                $qty_issuing = $qty_issuing->select([
                                    DB::raw('count(stock_deliveries.id) as qty_issuing'),
                                    DB::raw('DATE(stock_deliveries.date_received) as transaction_date')
                                ])
                                ->groupBy('transaction_date')
                                ->orderBy('transaction_date', 'ASC')
                                ->first();

                if (!empty($receiving_item) || !empty($qty_issuing)) {
                    $receiving = !empty($receiving_item) ? $receiving_item->qty_receiving : 0;
                    $delivery = !empty($qty_issuing) ? $qty_issuing->qty_issuing : 0;
                    $closing = ($beginings + $receiving) - $delivery;
                    // $item = !empty($item) ? $item->id : 0;
                    $result = [
                        'date' => $dateItem,
                        'begining' => $beginings,
                        'receiving' => $receiving,
                        'delivery' => $delivery,
                        'closing' => $closing,
                    ];
                    $beginings = $closing;
                    array_push($collections, $result);
                }
            }
        $date = $this->StartDate .' - '. $this->EndDate;
        // dd($collections);
        return view('exports.mutasigoodissue', [
            'collections' => $collections,
            'project' => $project,
            'tanggal_filter' => $date
        ]);
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:K6'; // All headers
                $event->sheet->setAutoFilter('A6:A6');
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
