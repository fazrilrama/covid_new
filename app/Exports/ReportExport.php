<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

use App\Item;
use App\Storage;


class ReportExport implements FromCollection, WithHeadingRow, WithHeadings, ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */

    //use Exportable;
    protected $itemDetailRepository;
    protected $type;

    public function __construct($collections,$type)
    {
        $this->collections = $collections; 
        $this->type = $type; 

        //type1 = stock on location
        //type2 = mutasi
        //type3 = handling in/out
        //type4 = sku transaction
        //type5 = stock management
        //type6 = staging in
        //type7 = staging out

        if($this->type == 4){
            foreach($this->collections as $collection){
                unset($collection->type); 
            }
        }
    }

    public function collection()
    {
        if($this->type == 1) {
            $response = [];

            $total_inbound = 0;
            $total_outbound = 0;

            foreach($this->collections->toArray() as $row) {
                
                $item = Item::find($row['item_id']);
                $storage = Storage::find($row['storage_id']);

                if($row['type_stock'] == 'in'){
                    $qty_in = number_format($row['qty'], 2, ',', '.');
                    $qty_out = number_format(0, 2, ',', '.');

                    $total_inbound += $row['qty'];
                }
                else{
                    $qty_in = number_format(0, 2, ',', '.');
                    $qty_out = number_format($row['qty'], 2, ',', '.');

                    $total_outbound += $row['qty'];
                }
                

                $response[] = [
                    'No Transaksi' => $row['code'],
                    'Item SKU / Name' => $item->sku.' / '.$item->name,
                    'Group Ref' => $row['ref_code'],
                    'Control Date' => $row['control_date'],
                    'UOM' => $item->default_uom->name,
                    'Storage' => $storage->code,
                    'Received' => $qty_in,
                    'Delivered' => $qty_out,
                ];
            }

            $response[] = [
                'No Transaksi' => NULL,
                'Item SKU / Name' => NULL,
                'Group Ref' => NULL,
                'Control Date' => NULL,
                'UOM' => NULL,
                'Storage' => NULL,
                'Received' => number_format($total_inbound, 2, ',', '.'),
                'Delivered' => number_format($total_outbound, 2, ',', '.'),
            ];

            $response[] = [
                'No Transaksi' => NULL,
                'Item SKU / Name' => NULL,
                'Group Ref' => NULL,
                'Control Date' => NULL,
                'UOM' => NULL,
                'Storage' => 'Total Tersedia',
                'Received' => number_format($total_inbound-$total_outbound, 2, ',', '.'),
                'Delivered' => NULL,
            ];

            return collect($response);
        }

        if($this->type== 16)
        {
            // $response = [];
            // <th>Kode GI</th>
            // <th>Origin</th>
            // <th>Destination</th>
            // <th>Shipper</th>
            // <th>Consignee</th>
            // <th>PIC</th>
            // <th>Status</th>
            // <th>Received By</th>
            // <th>Received Date</th>
            foreach($this->collections->toArray() as $row) {
                dd($row);
                // $response[] = [
                //     'Kode GI' => 
                // ] 
            }
            // $response[] = [
            //     'Kode GI' =>
            //     'Origin' =>
            //     'Destination' =>
            //     'Consignee' =>
            //     'PIC' =>
            //     'Status' => 
            //     'Received By' => 
            //     'Received Date' =>
            // ];
        }

        if($this->type == 3) {
            $response = [];

            // $itemQty = 0;
            // $itemWeight = 0;
            // $itemVolume = 0;
            // $rates = 0;
            // $total = 0;

            foreach($this->collections->toArray() as $row) {
                // $itemQty += $row['item_qty'];
                // $itemWeight += $row['item_weight'];
                // $itemVolume += $row['item_volume'];
                // $rates += $row['rates'];
                // $total += $row['total'];

                $response[] = [

                    "created_at" => $row['created_at'],
                    "item_code" => $row['item_code'],
                    "item_activity_name" => $row['item_activity_name'],
                    "item_warehouse_name" => $row['item_warehouse_name'],
                    "item_name" => $row['item_name'],
                    "item_qty" => $row['item_qty'],
                    "item_weight" => $row['item_weight'],
                    "item_volume" => $row['item_volume'],
                    "charge" => $row['charge'],
                    "tarif" => $row['tarif'],
                    "total_tarif" => $row['total_tarif'],
                ];
            }

            // $response[] = [
            //     "created_at" => NULL,
            //     "item_code" => NULL,
            //     "item_name" => 'Total',
            //     "item_qty" => $itemQty,
            //     "item_weight" => $itemWeight,
            //     "item_volume" => $itemVolume,
            //     "rates" => $rates,
            //     "total" => $total,
            // ];

            return collect($response);
        }
        if($this->type == 5) {
            $response = [];

            $total_inbound = 0;
            $total_outbound = 0;

            foreach($this->collections->toArray() as $row) {

                $item = Item::find($row['item_id']);
                $storage = Storage::find($row['storage_id']);

                if($row['type_stock'] == 'in'){
                    $qty_in = number_format($row['qty'], 2, ',', '.');
                    $qty_out = number_format(0, 2, ',', '.');

                    $total_inbound += $row['qty'];
                }
                else{
                    $qty_in = number_format(0, 2, ',', '.');
                    $qty_out = number_format($row['qty'], 2, ',', '.');

                    $total_outbound += $row['qty'];
                }
                
                $response[] = [
                    "Region" => $row['region_name'],
                    "Branch" => $row['branch_name'],
                    "No Transaksi" => $row['code'],                   
                    "Warehouse" => $row['warehouse_name'],
                    "Storage" => $storage->code,
                    "Item SKU / Name" => $item->sku.' / '.$item->name,
                    "Group Ref" => $row['ref_code'],
                    "Control Date" => $row['control_date'],
                    "UOM" => $item->default_uom->name,
                    "Received" => $qty_in,
                    "Delivered" => $qty_out,
                ];
            }

            $response[] = [
                "Region" => NULL,
                "Branch" => NULL,
                "No Transaksi" => NULL,
                "Warehouse" => NULL,
                "Storage" => NULL,
                "Item SKU / Name" => NULL,
                "Group Ref" => NULL,
                "Control Date" => NULL,
                "UOM" => NULL,               
                'Received' => number_format($total_inbound, 2, ',', '.'),
                'Delivered' => number_format($total_outbound, 2, ',', '.'),
            ];

            $response[] = [
                "Region" => NULL,
                "Branch" => NULL,
                "No Transaksi" => NULL,
                "Warehouse" => NULL,
                "Storage" => NULL,
                "Item SKU / Name" => NULL,
                "Group Ref" => NULL,
                "Control Date" => NULL,
                "UOM" => 'Total Tersedia',
                "Received" => number_format($total_inbound-$total_outbound, 2, ',', '.'),
                "Delivered" => NULL,
            ];

            return collect($response);
        }
        if($this->type == 6) {
            $response = [];

            $total_qty = 0;

            foreach($this->collections->toArray() as $row) {
                
                $item = Item::find($row['item_id']);
                
                $total_qty += $row['qty'];

                $response[] = [
                    'No Transaksi' => 'SA/IN:'.$row['code'],
                    'Item SKU / Name' => $item->sku.' / '.$item->name,
                    'Group Ref' => $row['ref_code'],
                    'Control Date' => $row['control_date'],
                    'UOM' => $item->default_uom->name,
                    'QTY' => number_format($row['qty'], 2, ',', '.'),
                ];
            }

            $response[] = [
                'No Transaksi' => NULL,
                'Item SKU / Name' => NULL,
                'Group Ref' => NULL,
                'Control Date' => NULL,
                'UOM' => 'Total Tersedia',
                'Received' => number_format($total_qty, 2, ',', '.'),
            ];

            return collect($response);
        }
        if($this->type == 7) {
            $response = [];

            $total_qty = 0;

            foreach($this->collections->toArray() as $row) {
                
                $item = Item::find($row['item_id']);
                $storage = Storage::find($row['storage_id']);
                
                $total_qty += $row['qty'];

                $response[] = [
                    'No Transaksi' => 'SA/IN:'.$row['code'],
                    'Item SKU / Name' => $item->sku.' / '.$item->name,
                    'Group Ref' => $row['ref_code'],
                    'Control Date' => $row['control_date'],
                    'UOM' => $item->default_uom->name,
                    'Storage' => $storage->code,
                    'QTY' => number_format($row['qty'], 2, ',', '.'),
                ];
            }

            $response[] = [
                'No Transaksi' => NULL,
                'Item SKU / Name' => NULL,
                'Group Ref' => NULL,
                'Control Date' => NULL,
                'UOM' => NULL,
                'Storage' => 'Total Tersedia',
                'Received' => number_format($total_qty, 2, ',', '.'),
            ];

            return collect($response);
        }

        return collect($this->collections);
    }

    public function headings(): array
    {
        if($this->type == 1){
            return [
                'No Transaksi',
                'Item SKU / Name',
                'Group Ref',
                'Control Date',
                'UOM',
                'Storage',
                'Received',
                'Delivered',
            ];
        }
        if($this->type == 2){
            return [
                'Date',
                'Item SKU & Name',
                'UOM',
                'Begining',
                'Receiving',
                'Instanding',
                'Delivery',
                'Outstanding',
                'closing',
            ];
        }
        if($this->type == 3){
            return [
                'Date',
                'Code',
                'Activity',
                'Warehouse',
                'Item',
                'Qty',
                'Weight',
                'Volume',
                'Charge',
                'Tarif',
                'Total Tarif',
            ];
        }
        if($this->type == 4){
            return [
                'No',
                'SKU',
                'Warehouse',
                'Transaction Number',
                'Date',
                'Qty',
                'Weight',
                'Volume',
                'UOM',
            ];
        }
        if($this->type == 5){
            return [
                "Region",
                "Branch",
                "No Transaksi",
                "Warehouse",
                "Storage",
                "Item SKU / Name",
                "Group Ref",
                "Control Date",
                "UOM",
                "Received",
                "Delivered",
            ];
        }
        if($this->type == 6){
            return [
                'No Transaksi',
                'Item SKU / Name',
                'Group Ref',
                'Control Date',
                'UOM',
                'QTY',
            ];
        }
        if($this->type == 7){
            return [
                'No Transaksi',
                'Item SKU / Name',
                'Group Ref',
                'Control Date',
                'UOM',
                'Storage',
                'QTY',
            ];
        }
        if($this->type == 10){
            return [
                'Branch',
                'Nama Gudang',
                'Kode Gudang',
                'Status',
                'QTY IN',
                'QTY OUT',
                'TURN OVER',
            ];
        }
        if($this->type == 11){
            // dd('masu,');
            // dd($this->collections);
            // $this->collections->toArray();
            return [
                'Kode Gudang',
                'Nama Gudang',
                'Region',
                'Branch',
                'Project',
                'Status',
                'Status Operasi',
                'No Kontrak ',
                'Awal Kontrak',
                'Akhir Kontrak',
                'Rented Space',
                'Commodity Name',
                'End Contract',
            ];
        }
        if($this->type == 12){
            return [
                'Kode Gudang',
                'Nama Gudang',
                'Region',
                'Branch',
                'Project',
                'Status',
            ];
        }
        if($this->type == 13){
            return [
                'Branch',
                'Gudang',
                'Project',
                'SKU',
                'Nama SKU',
                'UoM',
                'Stock Awal',
                'Masuk',
                'Keluar',
                'Stock Akhir'
            ];
        }
        
    }

    public function registerEvents(): array
    {
        $cellRange = '';
        if($this->type == 1){
            $cellRange = 'A1:H1';
        }
        if($this->type == 2){
            $cellRange = 'A1:G1';
        }
        if($this->type == 3){
            $cellRange = 'A1:K1';
        }
        if($this->type == 4){
            $cellRange = 'A1:I1';
        }
        if($this->type == 5){
            $cellRange = 'A1:K1';
        }
        if($this->type == 6){
            $cellRange = 'A1:F1';
        }
        if($this->type == 7){
            $cellRange = 'A1:G1';
        }
        if($this->type == 10){
            $cellRange = 'A1:G1';
        }
        if($this->type == 11){
            $cellRange = 'A1:M1';
        }
        if($this->type == 12){
            $cellRange = 'A1:G1';
        }
        if($this->type == 13){
            $cellRange = 'A1:J1';
        }

        return [
            AfterSheet::class    => function(AfterSheet $event) use($cellRange) {
                 // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);
            },
        ];
    }
}
