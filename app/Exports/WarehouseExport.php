<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Region;
use App\Warehouse;
use DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;


class WarehouseExport implements FromCollection, WithHeadings
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
        $warehouseLocations = Warehouse::where($regionConditions)
            ->join('parties', 'parties.id', 'warehouses.branch_id')
            ->join('cities as c', 'c.id', '=', 'warehouses.city_id')
            ->join('provinces as p', 'p.id', '=', 'warehouses.region_id')
            ->orderBy('parties.id')
            ->get([
                'warehouses.id',
                'warehouses.code',
                'c.name as area',
                'p.name as region',
                'warehouses.total_weight',
                'warehouses.total_volume',
                'warehouses.name as gd_name',
                'warehouses.latitude',
                'warehouses.length',
                'warehouses.width',
                'warehouses.longitude',
                'parties.name',
                'warehouses.is_active',
                'warehouses.ownership',
                'warehouses.status'
            ]);
        $count = 0;
        $count2 = 0;
        $wh1 = array();


        $export = [];
        

        foreach($warehouseLocations as $wl){
            $contracts = $wl->contracts();
            // dd($contracts);
            $total_rented_space = 0;
            foreach($contracts->where('is_active', 1)->get() as $contract) {
                $total_rented_space += $contract->pivot->rented_space;   
            }
            
            $total_space = $wl->length * $wl->width;
            if (!empty($total_space)) {
                $utility_space = ($total_rented_space > 0) ? round(($total_rented_space / $total_space)*100,2) : 0 ;
            }else {
                $utility_space = 0 ;
            }

            /*
         <th>Code:</th>
            <th>Name:</th>
            <th>Region:</th>
            <th>Branch:</th>
            <th>Total Luas Terpasang:</th>
            <th>Total Volume:</th>
            <th>Total Kapasitas(kg) Terpasang:</th>
            <th>Total Luas Terpakai(m<sup>2</sup>):</th>
            <th>Utilitas Gudang (Luas):</th>
            <th>Gudang</th>
            <th class="no-sort">
            Status
            <select class="select" name="status" id="select">
                <option value=" ">Semua</option>
                <option value=".Aktif">Aktif</option>
                <option value="Tidak Aktif">Tidak Aktif</option>
            </select>
            </th>
            <th>Operasi</th>
            <th>Action:</th>

        */
            $export[] = [
                'code' => $wl->code,
                'gd_name' => $wl->gd_name,
                'region' => $wl->region,
                'name' => $wl->name,
                'length' => number_format($wl->length*$wl->width, 2, ',', '.'),
                'Volume' => number_format($wl->total_volume, 2, ',', '.'),
                'weight' => number_format($wl->total_weight, 2, ',', '.'),
                'rented_space' => number_format($total_rented_space, 2, ',', '.'),
                'utility_space' => number_format($utility_space, 2, ',', '.'),
                'status' => $wl->is_active == 0 ? 'Tidak Aktif' : 'Aktif',
                'ownership' => $wl->ownership,
                'operasi'   => $wl->status
            ];
        }

        return collect($export);
    }

    public function headings(): array
    {

        /*
         <th>Code:</th>
            <th>Name:</th>
            <th>Region:</th>
            <th>Branch:</th>
            <th>Total Luas Terpasang:</th>
            <th>Total Volume:</th>
            <th>Total Kapasitas(kg) Terpasang:</th>
            <th>Total Luas Terpakai(m<sup>2</sup>):</th>
            <th>Utilitas Gudang (Luas):</th>
            <th>Gudang</th>
            <th class="no-sort">
            Status
            <select class="select" name="status" id="select">
                <option value=" ">Semua</option>
                <option value=".Aktif">Aktif</option>
                <option value="Tidak Aktif">Tidak Aktif</option>
            </select>
            </th>
            <th>Operasi</th>
            <th>Action:</th>

        */
        return [
            'Code',
            'Name',
            'Region',
            'Branch',
            'Total Luas Terpasang',
            'Total Volume',
            'Total Kapasitas(kg) Terpasang',
            'Total Luas Terpakai (m2)',
            'Utilitas Gudang (Luas)',
            'Gudang',
            'Status',
            'Operasi'
        ];
    }
}