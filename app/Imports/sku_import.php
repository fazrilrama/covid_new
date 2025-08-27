<?php

namespace App\Imports;

use App\Files;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class sku_import implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // $get_item = Item::where('sku',$row['sku'])
        //                 ->where('name',$row['nama'])
        //                 ->where('description',$row['description'])
        //                 ->where('default_uom_id',$row['default_uom'])
        //                 ->where('control_method_id',$row['control_method'])
        //                 ->where('commodity_id',$row['komoditas'])
        //                 ->where('length',$row['length'])
        //                 ->where('width',$row['width'])
        //                 ->where('height',$row['height'])
        //                 ->where('weight',$row['weight'])
        //                 ->where('volume',$row['volume'])
        //                 ->where('min_qty',$row['min_qty'])
        //                 ->where('additional_reference',$row['additional_ref'])
        //                 ->where('type_id',$row['jenis'])
        //                 ->where('packing_id',$row['packing'])
        //                 ->get();

        // if($get_item->count() == 0){
        //     return new Item([
        //         'sku'                   => $row['sku'],
        //         'name'                  => $row['nama'],
        //         'description'           => $row['description'],
        //         'default_uom_id'        => $row['default_uom'],
        //         'control_method_id'     => $row['control_method'],
        //         'commodity_id'          => $row['komoditas'],
        //         'length'                => $row['length'],
        //         'width'                 => $row['width'],
        //         'height'                => $row['height'],
        //         'weight'                => $row['weight'],
        //         'volume'                => $row['volume'],
        //         'min_qty'               => $row['min_qty'],
        //         'additional_reference'  => $row['additional_ref'],
        //         'type_id'               => $row['jenis'],
        //         'packing_id'            => $row['packing'],
        //     ]);
        // }

        $get_item = Files::where('nama_kapal',$row['nama_kapal'])
                        ->where('nama_file',$row['nama_file'])
                        ->where('nama_folder',$row['nama_folder'])
                        ->where('tanggal',$row['tanggal'])
                        ->where('perihal',$row['perihal'])
                        ->where('tipe_dokumen',$row['tipe_dokumen'])
                        ->where('jenis_kapal',$row['jenis_kapal'])
                        ->get();

        if($get_item->count() == 0){
            return new Files([
                'nama_kapal' => $row['nama_kapal'],
                'nama_file' => $row['nama_file'],
                'nama_folder' => $row['nama_folder'],
                'tanggal' => $row['tanggal'],
                'perihal' => $row['perihal'],
                'tipe_dokumen' => $row['tipe_dokumen'],
                'jenis_kapal' => $row['jenis_kapal'],
            ]);
        }
        
    }

    // public function headingRow(): int
    // {
    //     return 1;
    // }
}
