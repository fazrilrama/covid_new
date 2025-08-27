<?php

namespace App\Exports;

use App\StockEntryDetail;
use App\Warehouse;
use App\AdvanceNotice;
use App\Item;
use App\StockTransport;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;
use Auth;
use Carbon;
use Carbon\CarbonPeriod;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class MutasiStockExport implements FromView, ShouldAutoSize, WithEvents 
{
    protected $StartDate,$EndDate, $warehouse_id, $item_id;
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
        $from = $this->StartDate;
        $to = $this->EndDate;
        
        $item = Item::find($this->item_id);
        $collections = [];
        $jumlah_outhandling = 0;
        $jumlah_outhandling = 0;
        // dd($item);
        if(!empty($item)) {
            $begining_receiving = StockEntryDetail::join('stock_entries as se', 'se.id', '=', 'stock_entry_details.stock_entry_id')
                        ->where('se.type','inbound')
                        ->where(DB::raw('Date(se.updated_at)'),'<' ,$from)
                        ->where('stock_entry_details.item_id', $item->id)
                        ->where('se.project_id', $projectId)

                        ->where('se.status','Completed');
                        if($this->warehouse_id != '0' && $this->warehouse_id != null) {
                            $begining_receiving->where('se.warehouse_id', $this->warehouse_id);
                        }
                        $begining_receiving = $begining_receiving->select([
                            'se.id as stock_transport_id',
                            'stock_entry_details.item_id',
                            DB::raw('sum(stock_entry_details.qty) as qty'),
                        ])
                        ->sum('qty');
            // dd($begining_receiving)
            $begining_delivery = StockEntryDetail::join('stock_entries as se', 'se.id', '=', 'stock_entry_details.stock_entry_id')
                        ->where('se.type','outbound')
                        ->where(DB::raw('Date(se.updated_at)'), '<' ,$from)
                        ->where('stock_entry_details.item_id', $item->id)
                        ->where('se.project_id', $projectId)
                        ->where('se.status','Completed');
                        
                    if($this->warehouse_id != '0' && $this->warehouse_id != null) {
                        $begining_delivery
                        ->where('se.warehouse_id',$this->warehouse_id);
                    }
                    $begining_delivery = $begining_delivery->select([
                            'se.id as stock_delivery_id',
                            'stock_entry_details.item_id',
                            DB::raw('sum(stock_entry_details.qty) as qty'),
                        ])
                        ->sum('qty');


            $master_standing = AdvanceNotice::join('stock_advance_notice_details', 'stock_advance_notices.id', 'stock_advance_notice_details.stock_advance_notice_id')
            // ->where(DB::raw('Date(stock_advance_notices.created_at)'), '<=' ,$data['date_to'])
            ->where('stock_advance_notice_details.item_id', $item->id)
            ->where('stock_advance_notices.project_id', $projectId)
            ->where('stock_advance_notices.status' , '<>', 'Closed');

            if($this->warehouse_id != '0' && $this->warehouse_id != null) {
            $master_standing
            ->where('stock_advance_notices.warehouse_id', $this->warehouse_id);
            }
            $master_standing = $master_standing->select(DB::raw('sum(stock_advance_notice_details.qty) as qty_details,stock_advance_notice_id'))->groupBy('stock_advance_notice_id');
            // dd($master_standing->where('stock_advance_notices.type', 'inbound')toSql);
            // $inbound = (clone $staging)->where('st.type', 'inbound')->first();
            $inbound_details = (clone $master_standing)->where('stock_advance_notices.type', 'inbound')->pluck('qty_details', 'stock_advance_notice_id');
            $outbound_details = (clone $master_standing)->where('stock_advance_notices.type', 'outbound')->pluck('qty_details', 'stock_advance_notice_id');
            // dd($master_standing->toSql());
            // dd($outbound_details);
            // dd($inbound_details);
            $master_transport = StockTransport::join('stock_transport_details', 'stock_transport_details.stock_transport_id', 'stock_transports.id')
            ->where('stock_transport_details.item_id', $item->id)
            ->where('stock_transports.project_id', $projectId)
            ->where('stock_transports.status' , '<>', 'Closed');

            if($this->warehouse_id != '0' && $this->warehouse_id != null) {
            $master_transport = $master_transport
            ->where('stock_transports.warehouse_id', $this->warehouse_id);
            }

            // foreach ($)
            $arr = collect();
            $outarr = collect();
            $total_inbound = 0;
            foreach ($inbound_details as $key => $inbound_detail) {
            if($key != ''){
                $arr->push($key);
            } else {
                $arr->push(0);
            }
            if($inbound_detail != null) {
                $total_inbound += $inbound_detail;
            } else {
                $total_inbound += 0;
            }
            }

            $total_outbound = 0;
            // dd($outbound_details);
            foreach ($outbound_details  as $key => $outbound_detail) {
            if($key != ''){
                $outarr->push($key);
            } else {
                $outarr->push(0);
            }
            if($outbound_detail != null) {
                $total_outbound += $outbound_detail;
            } else {
                $total_outbound += 0;
            }
            }

            $inbound_transport = (clone $master_transport)->where('stock_transports.type', 'inbound')->whereIn('advance_notice_id', $arr)->sum('qty');
            $outbound_transport = (clone $master_transport)->where('stock_transports.type', 'outbound')->whereIn('advance_notice_id', $outarr)->sum('plan_qty');
            // dd($outbound_transport);
            // dd($total_outbound,$outbound_transport);

            $jumlah_inhandling = $total_inbound - $inbound_transport;
            $jumlah_outhandling = $total_outbound - $outbound_transport;
            // dd($outbound_transport);
            // $begining = 0;
            // dd($begining_delivery);
            $beginings = $begining_receiving - $begining_delivery;
            // dd($beginings);
            $periods = CarbonPeriod::create($from, $to);
            // dd(count($periods) - 19);
            $i = 0;
            $countPeriods = count($periods);
            // dd($collections);
            foreach ($periods as $period) {
                $dateItem = $period->format('Y-m-d');
                $receiving_item = StockEntryDetail::join('stock_entries as se', 'se.id', '=', 'stock_entry_details.stock_entry_id')
                            ->where('se.type','inbound')
                            ->where(DB::raw('Date(se.updated_at)'), $dateItem)
                            ->where('stock_entry_details.item_id', $item->id)
                            ->where('se.project_id', $projectId)
                            ->where('se.status','Completed');
                            if($this->warehouse_id != '0' && $this->warehouse_id != null) {
                                $receiving_item->where('se.warehouse_id', $this->warehouse_id);
                            }
                            $receiving_item = $receiving_item->select([
                                'se.id as stock_transport_id',
                                'stock_entry_details.item_id',
                                DB::raw('sum(stock_entry_details.qty) as qty_receiving'),
                                DB::raw('DATE(se.updated_at) as transaction_date'),
                            ])
                            ->groupBy('transaction_date')
                            ->orderBy('transaction_date', 'ASC')
                            ->first();

                $qty_issuing = StockEntryDetail::join('stock_entries as se', 'se.id', '=', 'stock_entry_details.stock_entry_id')
                                ->where('se.type','outbound')
                                ->where(DB::raw('Date(se.updated_at)'), $dateItem)
                                ->where('stock_entry_details.item_id', $item->id)
                                ->where('se.project_id', $projectId)
                                ->where('se.status','Completed');
                                if($this->warehouse_id != '0' && $this->warehouse_id != null) {
                                    $qty_issuing->where('se.warehouse_id', $this->warehouse_id);
                                }
                                $qty_issuing = $qty_issuing->select([
                                    'se.id as stock_delivery_id',
                                    'stock_entry_details.item_id',
                                    DB::raw('sum(stock_entry_details.qty) as qty_issuing'),
                                    DB::raw('DATE(se.updated_at) as transaction_date')
                                ])
                                ->groupBy('transaction_date')
                                ->orderBy('transaction_date', 'ASC')
                                ->first();
                // staging
                $a = 0;
                if (!empty($receiving_item) || !empty($qty_issuing)) {
                    // $a+=1;
                    $receiving = !empty($receiving_item) ? $receiving_item->qty_receiving : 0;
                    $delivery = !empty($qty_issuing) ? $qty_issuing->qty_issuing : 0;
                    // $instanding_inbound = !empty($inbound) ? $inbound->qty_details_staging - $inbound->qty_transport_staging : 0;
                    // $outstanding_outbound = !empty($outbound) ? $outbound->qty_details_staging - $outbound->qty_transport_staging: 0;
                    $closing = ($beginings + $receiving) - $delivery;
                    $result = [
                        'date' => $dateItem,
                        'item' => $item->sku.' '.$item->name,
                        'uom_name' => $item->default_uom->name,
                        'begining' => $beginings,
                        'receiving' => $receiving,
                        'instanding' => $i == 0 ? $jumlah_inhandling : '0',
                        'delivery' => $delivery,
                        'outstanding' => $i == 0 ? $jumlah_outhandling : '0',
                        'closing' => $closing,
                    ];
                    
                    $beginings = $closing;
                    array_push($collections, $result);
                    // array_reverse($collections,true);
                    // dd($collections);
                    $i++;
                    // $a+1;
                }
            // echo $i;
            // array_push($collections, $jumlah_inhandling, $jumlah_outhandling);
            }
        }

        // dd($from, $to);
        
        // begining
        
        
        $date = $this->StartDate.' s/d '.$this->EndDate;
        // dd($fix_arr);
        return view('exports.mutasistock', [
            'stocks' => $collections,
            'project' => session()->get('current_project')->name,
            'date' => $date,
            'jumlah_outhandling' => $jumlah_outhandling, 
            'jumlah_inhandling' => $jumlah_inhandling
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
