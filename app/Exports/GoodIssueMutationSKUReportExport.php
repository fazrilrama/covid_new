<?php

namespace App\Exports;

use App\StockTransportDetail;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;
use Carbon\CarbonPeriod;
use App\AssignedTo;
use Auth;
use Carbon\Carbon;
use App\StockDeliveryDetail;
use App\ItemProjects;
use App\Project;    
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class GoodIssueMutationSKUReportExport implements FromView, ShouldAutoSize, WithEvents 
{
    protected $project_id,$StartDate;
    public function __construct(String  $project_id = null, String  $StartDate = null)
    {
        $this->project_id = $project_id;
        $this->StartDate = $StartDate;
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
            $assign_to = AssignedTo::where('user_id', Auth::user()->id)->pluck('project_id');
            // dd($assign_to);
            if($this->project_id != 'all'){
                $item_finds = ItemProjects::where('project_id', $this->project_id)->get();
            } else {
                $item_finds = ItemProjects::whereIn('project_id', $assign_to)->get();
            }
            // dd($item_finds);
        
            $collections = [];
            // dd($beginings);
            $periods = CarbonPeriod::create($this->StartDate, $this->StartDate);
            // dd(count($periods));
            $i = 0;
            $countPeriods = count($periods);
            // dd($collections);
            foreach ($periods as $period) {
                foreach ($item_finds as $key => $item_find) {
                    // dd('$masuk_sini');
                    $dateItem = $period->format('Y-m-d');


                    $begining_delivery_all = StockDeliveryDetail::where(DB::raw('Date(stock_deliveries.created_at)'), '<' ,$this->StartDate)
                    ->join('stock_deliveries', 'stock_deliveries.id', 'stock_delivery_details.stock_delivery_id')
                    ->join('stock_entries', 'stock_entries.id', 'stock_deliveries.stock_entry_id')
                    ->where('stock_delivery_details.item_id', $item_find->item->id)
                    ->whereIn('warehouse_id', ['892', '1385']);
                            if($this->project_id != 'all') 
                            {
                                $begining_delivery_all
                                ->where('stock_deliveries.project_id', $this->project_id); 
                            }
                            $begining_delivery_all->whereIn('stock_deliveries.status',['Received', 'Completed']);
                        // ->where('stock_deliveries.date_received', '<>', null);    
                    $begining_delivery_all = $begining_delivery_all->select([
                                'stock_deliveries.id as stock_delivery_id',
                            ])
                            ->sum('qty');
                    // dd($begining_delivery);

                    $receiving_item_akumulasi = StockDeliveryDetail::where(DB::raw('Date(stock_deliveries.date_received)'),'<', Carbon::parse($dateItem))
                        ->join('stock_deliveries', 'stock_deliveries.id', 'stock_delivery_details.stock_delivery_id')
                        ->join('stock_entries', 'stock_entries.id', 'stock_deliveries.stock_entry_id')
                        ->where('stock_delivery_details.item_id', $item_find->item->id)
                        ->whereIn('warehouse_id', ['892', '1385']);

                        // ->where('stock_deliveries.project_id', $projectId)
                        if($this->project_id != 'all') 
                        {
                            $receiving_item_akumulasi
                            ->where('stock_deliveries.project_id', $this->project_id); 
                        }
                        $receiving_item_akumulasi->whereIn('stock_deliveries.status',['Received']);
                        $receiving_item_akumulasi = $receiving_item_akumulasi->select([
                            DB::raw('stock_deliveries.id, stock_delivery_details.item_id'),
                            DB::raw('sum(stock_delivery_details.qty) as qty_akumulasi'),
                        ])
                    ->first();

                    $receiving_item_all = StockDeliveryDetail::where(DB::raw('Date(stock_deliveries.date_received)'), '<' ,$this->StartDate)
                    ->join('stock_deliveries', 'stock_deliveries.id', 'stock_delivery_details.stock_delivery_id')
                    ->join('stock_entries', 'stock_entries.id', 'stock_deliveries.stock_entry_id')
                    ->where('stock_delivery_details.item_id', $item_find->item->id)
                    ->whereIn('warehouse_id',[ '892', '1385']);
                            if($this->project_id != 'all') 
                            {
                                $receiving_item_all
                                ->where('stock_deliveries.project_id', $this->project_id); 
                            }
                            $receiving_item_all->whereIn('stock_deliveries.status',['Received']);
                        // ->where('stock_deliveries.date_received', '<>', null);    
                    $receiving_item_all = $receiving_item_all->select([
                                'stock_deliveries.id as stock_delivery_id',
                            ])
                            ->sum('qty');
        

                    $beginings = $begining_delivery_all - $receiving_item_all;


                    $receiving_item = StockDeliveryDetail::where(DB::raw('Date(stock_deliveries.created_at)'), $dateItem)
                                ->join('stock_deliveries', 'stock_deliveries.id', 'stock_delivery_details.stock_delivery_id')
                                ->join('stock_entries', 'stock_entries.id', 'stock_deliveries.stock_entry_id')
                                ->where('stock_delivery_details.item_id', $item_find->item->id)
                                ->whereIn('warehouse_id', ['892', '1385']);

                                // ->where('stock_deliveries.project_id', $projectId)
                                if($this->project_id != 'all') 
                                {
                                    $receiving_item
                                    ->where('stock_deliveries.project_id', $this->project_id); 
                                }
                                $receiving_item->whereIn('stock_deliveries.status',['Completed', 'Received']);
                                $receiving_item = $receiving_item->select([
                                    DB::raw('stock_deliveries.id, stock_delivery_details.item_id'),
                                    DB::raw('sum(stock_delivery_details.qty) as qty_receiving'),
                                    DB::raw('DATE(stock_deliveries.created_at) as transaction_date'),
                                ])
                                ->groupBy('transaction_date')
                                ->orderBy('transaction_date', 'ASC')
                                ->first();
                    // dd($receiving_item);

                    $qty_issuing = StockDeliveryDetail::where(DB::raw('Date(stock_deliveries.date_received)'), $dateItem)
                    ->join('stock_deliveries', 'stock_deliveries.id', 'stock_delivery_details.stock_delivery_id')
                    ->join('stock_entries', 'stock_entries.id', 'stock_deliveries.stock_entry_id')
                    ->whereIn('warehouse_id', ['892', '1385'])
                    ->where('stock_delivery_details.item_id', $item_find->item->id);
                                    if($this->project_id != 'all') 
                                    {
                                        $qty_issuing
                                        ->where('stock_deliveries.project_id', $this->project_id); 
                                    }
                                    $qty_issuing
                                    ->where('stock_deliveries.status','Received')
                                    ->where('stock_deliveries.date_received', '<>', null);
                                    $qty_issuing = $qty_issuing->select([
                                        DB::raw('count(stock_deliveries.id), stock_delivery_details.item_id'),
                                        DB::raw('sum(stock_delivery_details.qty) as qty_issuing'),
                                        DB::raw('DATE(stock_deliveries.date_received) as transaction_date')
                                    ])
                                    ->groupBy('transaction_date')
                                    ->orderBy('transaction_date', 'ASC')
                                    ->first();

                    // if (!empty($receiving_item) || !empty($qty_issuing)) {
                        $receiving = !empty($receiving_item) ? $receiving_item->qty_receiving : 0;
                        $delivery = !empty($qty_issuing) ? $qty_issuing->qty_issuing : 0;
                        $closing = ($beginings + $receiving) - $delivery;
                        $akumulasi_all = !empty($receiving_item_akumulasi) ? $receiving_item_akumulasi->qty_akumulasi : 0;
                        // $item = !empty($item) ? $item->id : 0;
                        $result = [
                            'date' => $dateItem,
                            'begining' => $beginings,
                            'receiving' => $receiving,
                            'delivery' => $delivery,
                            // 'closing' => $closing,
                            'item' => $item_find->item->name,
                            'sku' => $item_find->item->sku,
                            'uom_name' => $item_find->item->default_uom->name,
                            'project' => $this->project_id,
                            'akumulasi' => $akumulasi_all+ $delivery
                        ];
                        $beginings = $closing;
                        array_push($collections, $result);
                    // }
                }
            }
        $date = $this->StartDate;
        // dd($collections);
        return view('exports.gi_sku', [
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
