<?php

namespace App\Exports;

use App\Invoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Region;
use App\Warehouse;
use DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;


class DivreWarehouseExport implements FromCollection, WithHeadings
{
    use Exportable;

    public function collection()
    {
        $selectedRegion = '';
        $regionConditions = [];
        if (!empty($selectedRegion)) {
            $regionConditions = ['region_id' => $selectedRegion];
        }

        $regions = Region::get(['id', 'name']);
        $warehouseLocations = Warehouse::whereNotNull('warehouses.latitude')
            ->where('is_active', 1)
            ->whereNotNull('warehouses.longitude')
            ->where($regionConditions)
            ->join('parties', 'parties.id', 'warehouses.branch_id')
            ->join('cities as c', 'c.id', '=', 'warehouses.city_id')
            ->join('provinces as p', 'p.id', '=', 'warehouses.region_id')
            ->orderBy('parties.id')
            ->get([
                'warehouses.id',
                'warehouses.code',
                'c.name as area',
                'p.name as region',
                'warehouses.total_weight as total_volume',
                'warehouses.name as gd_name',
                'warehouses.latitude',
                'warehouses.length',
                'warehouses.width',
                'warehouses.longitude',
                DB::raw('(SELECT type FROM stock_entry_details AS sed
                            JOIN stock_entries AS se ON se.id = sed.stock_entry_id
                            JOIN storages ON storages.id = sed.storage_id
                            WHERE storages.warehouse_id = warehouses.id
                            ORDER BY sed.created_at DESC
                            LIMIT 1) as last_activity'),
                DB::raw('(SELECT sed.created_at FROM stock_entry_details AS sed
                            JOIN stock_entries AS se ON se.id = sed.stock_entry_id
                            JOIN storages ON storages.id = sed.storage_id
                            WHERE storages.warehouse_id = warehouses.id
                            ORDER BY sed.created_at DESC
                            LIMIT 1) as last_activity_date'),
                'parties.name'
            ]);

        $count = 0;
        $count2 = 0;
        $wh1 = array();
        foreach ($warehouseLocations as $wh) {

            $wh->total_space = $wh->length*$wh->width;
            $wh->total_rented_space = 0;
            $wh->utility_space = 0;
            foreach ($wh->contracts()->where('is_active', 1)->get() as $contract) {
                $wh->total_rented_space += $contract->pivot->rented_space;
            }

            if($wh->length == 0 || $wh->width ==0){
                $wh->utility_space = 99999999;
                $count +=1;
                array_push($wh1, $wh);
            }else{
                if (empty($wh->total_rented_space)) {
                    $count +=1;
                    $wh->utility_space = 0;
                    array_push($wh1, $wh);
                }else{
                    $wh->utility_space = ($wh->total_rented_space/$wh->total_space)*100;
                    // $wh->utility_space = round(($wh->total_rented_space/
                    // $wh->total_space)*100,2);
                    if ($wh->utility_space >0) {
                        # code...
                    $count2 += 1;
                    }
                    // $wh1 = $wh;
                    array_push($wh1, $wh);
                }
            }
        }
        
        

        $export = [];

        foreach($warehouseLocations as $wl){
            $export[] = [
                'code' => $wl->code,
                'gd_name' => $wl->gd_name,
                'area' => $wl->area,
                'region' => $wl->region,
                'name' => $wl->name,
                'latitude' => $wl->latitude,
                'longitude' => $wl->longitude,
                'length' => "{$wl->length}",
                'width' => "{$wl->width}",
                'total_volume' => "{$wl->total_volume}",
                'total_space' => "{$wl->total_space}",
                'total_rented_space' => "{$wl->total_rented_space}",
                'utility_space' => "{$wl->utility_space}",
                'status' => $wl->utility_space > 0 ? 'TERISI' : 'BELUM TERISI'
            ];
        }

        return collect($export);
    }

    public function headings(): array
    {
        return [
            'Kode Gudang',
            'Nama Gudang',
            'Area',
            'Regional',
            'Divre/SubDivre',
            'Latitude',
            'Longitude',
            'Length',
            'Width',
            'Total Volume',
            'Total Space',
            'Total Rent Space',
            'Utility Space',
            'Status'
        ];
    }
}