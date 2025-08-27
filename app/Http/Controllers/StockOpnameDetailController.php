<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\StockOpname;
use App\StockOpnameDetail;
use App\Storage;
use App\StockEntryDetail;

class StockOpnameDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(StockOpname $stock_opname)
    {
        $action = route('stock_opname_details.store', $stock_opname->id);
        $method = 'POST';
        // dd($stock_opname->warehouse_id);
        $warehouse_item = StockEntryDetail::join('stock_entries', 'stock_entries.id', 'stock_entry_details.stock_entry_id')
        ->join('items', 'items.id', 'stock_entry_details.item_id')
        ->where('stock_entries.warehouse_id', $stock_opname->warehouse_id)
        ->select(['items.sku','items.name', 'items.id', 'stock_entries.project_id', 'items.default_uom_id'])
        ->groupBy('stock_entry_details.item_id')
        ->get();
        
        $storages = Storage::where('warehouse_id', $stock_opname->warehouse_id)->get();

        $stock_opname_detail = new StockOpnameDetail;

        return view('stock_opname_details.create')->with([
            'action' => $action,
            'method' => $method,
            'stock_opname' => $stock_opname,
            'warehouse_item' => $warehouse_item,
            'stock_opname_detail' => $stock_opname_detail,
            'storages' => $storages
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($stock_opname, Request $request)
    {
        // dd($stock_opname);
        $request->validate([
            'so_awal' => 'required',
            'stock_awal_adm' => 'required',
            'item' => 'required'
        ]);

        $phisik_awal = round($request->stock_awal_adm, 3) -round($request->sto_awal, 3) +round($request->so_awal, 3);
        $stock_akhir_adm = round($request->stock_awal_adm,3) + round($request->realisasi_adm_penerimaan,3) + round($request->susut_bar) - round($request->realisasi_adm_pengeluaran);
        $po_sto_akhir = round($request->sto_awal,3) + round($request->realisasi_adm_penerimaan,3) - round($request->realisasi_phisik_penerimaan, 3) - round($request->susut_bar, 3);

        $so_akhir = round($request->so_awal, 3) + round($request->realisasi_adm_pengeluaran,3) -  round($request->realisasi_phisik_pengeluaran, 3);
        $stock_phisik_akhir = $phisik_awal + round($request->realisasi_phisik_penerimaan, 3) - round($request->realisasi_phisik_pengeluaran, 3);
        $selisih = $stock_phisik_akhir - round($request->stock_taking_akhir,3);
        
        $checkExist = StockOpnameDetail::where('stock_opname_id', $stock_opname)->where('item_id', $request->item)->where('project_id', $request->project_id)->where('storage_id', $request->storage_id)->first();
        if($checkExist)
        {
            $checkExist->wina_stock += $request->stock_wina;
            $checkExist->sto_awal += $request->sto_awal;
            $checkExist->so_awal += $request->so_awal;
            $checkExist->stock_awal_adm += $request->stock_awal_adm;
            $checkExist->phisik_awal += $phisik_awal;
            $checkExist->realisasi_adm_penerimaan += $request->realisasi_adm_penerimaan;
            $checkExist->realisasi_phisik_penerimaan += $request->realisasi_phisik_penerimaan;
            $checkExist->susut_bar += $request->susut_bar;
            $checkExist->realisasi_adm_pengeluaran += $request->realisasi_adm_pengeluaran;
            $checkExist->realisasi_phisik_pengeluaran += $request->realisasi_phisik_pengeluaran;
            $checkExist->stock_akhir_adm += $stock_akhir_adm;
            $checkExist->po_sto_akhir += $po_sto_akhir;
            $checkExist->so_akhir += $so_akhir;
            $checkExist->stock_phisik_akhir += $stock_phisik_akhir;
            $checkExist->storage_id = $request->storage_id;
            $checkExist->stock_taking_akhir += $request->stock_taking_akhir;
            $checkExist->selisih += $selisih;
            $checkExist->save();

        } else{
            $stockOpnameDetail = new StockOpnameDetail;
            $stockOpnameDetail->wina_stock = $request->stock_wina;
            $stockOpnameDetail->uom_id = $request->uom_id;
            $stockOpnameDetail->sto_awal = $request->sto_awal;
            $stockOpnameDetail->item_id = $request->item;
            $stockOpnameDetail->storage_id = $request->storage_id;
            $stockOpnameDetail->so_awal = $request->so_awal;
            $stockOpnameDetail->stock_awal_adm = $request->stock_awal_adm;
            $stockOpnameDetail->phisik_awal = $phisik_awal;
            $stockOpnameDetail->realisasi_adm_penerimaan = $request->realisasi_adm_penerimaan;
            $stockOpnameDetail->realisasi_phisik_penerimaan = $request->realisasi_phisik_penerimaan;
            $stockOpnameDetail->susut_bar = $request->susut_bar;
            $stockOpnameDetail->realisasi_adm_pengeluaran = $request->realisasi_adm_pengeluaran;
            $stockOpnameDetail->realisasi_phisik_pengeluaran = $request->realisasi_phisik_pengeluaran;
            $stockOpnameDetail->stock_akhir_adm = $stock_akhir_adm;
            $stockOpnameDetail->po_sto_akhir = $po_sto_akhir;
            $stockOpnameDetail->so_akhir = $so_akhir;
            $stockOpnameDetail->stock_phisik_akhir = $stock_phisik_akhir;
            $stockOpnameDetail->stock_taking_akhir = $request->stock_taking_akhir;
            $stockOpnameDetail->selisih = $selisih;
            $stockOpnameDetail->stock_opname_id = $stock_opname;
            $stockOpnameDetail->project_id = $request->project_id;
            $stockOpnameDetail->save();
        }
        

        return redirect('stock_opnames/'.$stock_opname.'/edit')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(StockOpnameDetail $stock_opname_detail)
    {
        $action = route('stock_opname_details.update', $stock_opname_detail->id);
        $method = 'PUT';
        // dd($stock_opname->warehouse_id);
        $warehouse_item = StockEntryDetail::join('stock_entries', 'stock_entries.id', 'stock_entry_details.stock_entry_id')
        ->join('items', 'items.id', 'stock_entry_details.item_id')
        ->where('stock_entries.warehouse_id', $stock_opname_detail->header->warehouse_id)
        ->select(['items.sku','items.name', 'items.id'])
        ->groupBy('stock_entry_details.item_id')
        ->get();
        $stock_opname = StockOpname::find($stock_opname_detail->stock_opname_id);
        $storages = Storage::where('warehouse_id', $stock_opname->warehouse_id)->get();
        // dd($stock_opname);
        // dd($warehouse_item);
        return view('stock_opname_details.edit')->with([
            'action' => $action,
            'method' => $method,
            'stock_opname' => $stock_opname,
            'warehouse_item' => $warehouse_item,
            'stock_opname_detail' => $stock_opname_detail,
            'storages' => $storages
        ]);   
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StockOpnameDetail $stock_opname_detail, Request $request)
    {
        $phisik_awal = round($request->stock_awal_adm, 3) -round($request->sto_awal, 3) +round($request->so_awal, 3);
        $stock_akhir_adm = round($request->stock_awal_adm,3) + round($request->realisasi_adm_penerimaan,3) + round($request->susut_bar) - round($request->realisasi_adm_pengeluaran);
        $po_sto_akhir = round($request->sto_awal,3) + round($request->realisasi_adm_penerimaan,3) - round($request->realisasi_phisik_penerimaan, 3) - round($request->susut_bar, 3);

        $so_akhir = round($request->so_awal, 3) + round($request->realisasi_adm_pengeluaran,3) -  round($request->realisasi_phisik_pengeluaran, 3);
        $stock_phisik_akhir = $phisik_awal + round($request->realisasi_phisik_penerimaan, 3) - round($request->realisasi_phisik_pengeluaran, 3);
        
        $selisih = $stock_phisik_akhir - round($request->stock_taking_akhir,3);
    
        $stock_opname_detail->sto_awal = $request->sto_awal;
        $stock_opname_detail->so_awal = $request->so_awal;
        $stock_opname_detail->stock_awal_adm = $request->stock_awal_adm;
        $stock_opname_detail->phisik_awal = $phisik_awal;
        $stock_opname_detail->realisasi_adm_penerimaan = $request->realisasi_adm_penerimaan;
        $stock_opname_detail->realisasi_phisik_penerimaan = $request->realisasi_phisik_penerimaan;
        $stock_opname_detail->susut_bar = $request->susut_bar;
        $stock_opname_detail->realisasi_adm_pengeluaran = $request->realisasi_adm_pengeluaran;
        $stock_opname_detail->realisasi_phisik_pengeluaran = $request->realisasi_phisik_pengeluaran;
        $stock_opname_detail->stock_akhir_adm = $stock_akhir_adm;
        $stock_opname_detail->po_sto_akhir = $po_sto_akhir;
        $stock_opname_detail->so_akhir = $so_akhir;
        $stock_opname_detail->stock_phisik_akhir = $stock_phisik_akhir;
        $stock_opname_detail->stock_taking_akhir = $request->stock_taking_akhir;
        $stock_opname_detail->selisih = $selisih;
        $stock_opname_detail->storage_id = $request->storage_id;
        $stock_opname_detail->save();

        return redirect('stock_opnames/'.$stock_opname_detail->stock_opname_id.'/edit')->with('success', 'Data berhasil disimpan');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(StockOpnameDetail $stock_opname_detail)
    {
        $stock_opname_detail->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}
