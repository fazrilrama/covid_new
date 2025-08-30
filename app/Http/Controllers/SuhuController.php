<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Warehouse;
use App\SuhuModel;
use App\StatusSuhuModel;

class SuhuController extends Controller
{
    public function index() {
        $status = StatusSuhuModel::get();
        $suhu = SuhuModel::select(
            'suhu.*', 
            'status_suhu.name as tindakan_name'
        )
        ->leftJoin('status_suhu', 'status_suhu.id', '=', 'suhu.tindakan_id')
        ->get();

        return view('suhu.index', compact('status', 'suhu'));
    }

    public function get(Request $request) {
        $data = $request->all();

        $suhu = SuhuModel::where('id', $data['id'])->first();
        return response()->json([
            "status" => true,
            "message" => 'Data berhasil diambil',
            "data" => $suhu
        ]);
    }

    public function remove(Request $request) {
        $data = $request->all();

        DB::beginTransaction();

        try {

            $check_suhu = SuhuModel::where('id', $data['id'])->first();

            if($check_suhu) {
                $check_suhu->delete();
            }

            DB::commit();
            return response()->json([
                "status" => true,
                "message" => 'Data berhasil dihapus',
            ]);

        } catch (\Exception $e) {

            DB::rollback();
            return response()->json([
                "status"  => false,
                "message" => "ERROR",
                "error"   => $e->getMessage(), // buat debug
            ], 500);
        }
    }


    public function update(Request $request) {
        $validator = Validator::make($request->all(), [
            'id_edit'          => 'required|exists:suhu,id',
            'time_edit'      => 'required',
            'petugas_edit'     => 'required',
            'anter_room_edit'  => 'required',
            'chamber_2_edit'  => 'required',
            'chamber_3_edit'  => 'required',
            'kulkas_edit'  => 'required',
            'tindakan_edit'  => 'required',
            'catatan_edit'  => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status"  => false,
                "message" => "VALIDATION_ERROR",
                "errors"  => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $data = $request->all();

            $mappedData = [
                'time'       => $data['time_edit'],
                'petugas'    => $data['petugas_edit'],
                'anter_room' => $data['anter_room_edit'],
                'chamber_2'  => $data['chamber_2_edit'],
                'chamber_3'  => $data['chamber_3_edit'],
                'kulkas'     => $data['kulkas_edit'],
                'tindakan_id'   => $data['tindakan_edit'],
                'catatan'    => $data['catatan_edit'],
            ];

            // filter hanya yang tidak null
            $filteredData = array_filter($mappedData, function ($value) {
                return !is_null($value);
            });

            // update hanya field yang dikirim
            SuhuModel::where('id', $data['id_edit'])->update($filteredData);
        
            DB::commit();
            return response()->json([
                "status" => true,
                "message" => 'Data berhasil diupdate'
            ]);

        } catch (\Exception $e) {

            DB::rollback();
            return response()->json([
                "status"  => false,
                "message" => "ERROR",
                "error"   => $e->getMessage(), // buat debug
            ], 500);
        }
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'time'      => 'required',
            'petugas'     => 'required',
            'anter_room'  => 'required',
            'chamber_2'  => 'required',
            'chamber_3'  => 'required',
            'kulkas'  => 'required',
            'tindakan'  => 'required',
            'catatan'  => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status"  => false,
                "message" => "VALIDATION_ERROR",
                "errors"  => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $data = $request->all();

            $insert = SuhuModel::create([
                'tindakan_id' => $data['tindakan'],
                'time' => $data['time'],
                'petugas' => $data['petugas'],
                'anter_room' => $data['anter_room'],
                'chamber_2' => $data['chamber_2'],
                'chamber_3' => $data['chamber_3'],
                'kulkas' => $data['kulkas'],
                'catatan' => $data['catatan'],
            ]);


            DB::commit();
            return response()->json([
                "status" => true,
                "message" => 'Data berhasil dibuat',
                "data" => $insert
            ]);

        } catch (\Exception $e) {

            DB::rollback();
            return response()->json([
                "status"  => false,
                "message" => "ERROR",
                "error"   => $e->getMessage(), // buat debug
            ], 500);
        }


        
    }
}
