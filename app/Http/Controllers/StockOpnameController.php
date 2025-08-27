<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use App\StockOpname;
use App\StockOpnameDetail;
use App\Warehouse;  
use Auth;
use Carbon\Carbon;
use DB;
use App\StockEntryDetail;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportExport;
use App\Exports\StockOpnameExport;

class StockOpnameController extends Controller
{
    
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            if ( $user->hasRole('Superadmin') ) {
            }
            if ( $user->hasRole('Admin') || $user->hasRole('Admin-BGR') || $user->hasRole('Admin-Client') ) {
                if (empty(session()->get('current_project'))) {
                    return redirect('empty-project');
                }
            }else if (empty(session()->get('current_project'))) {
                return redirect('empty-project');
            }

            return $next($request);
        });
    }

    public function index() {
        if(Auth::user()->hasRole('WarehouseSupervisor')) {
            $collections = StockOpname::where('warehouse_id', session()->get('warehouse_id'))->get();
        } else {
            $user = \App\User::find(Auth::user()->id);
            $manager_warehouse = Warehouse::where('branch_id', $user->branch_id)->pluck('id');
            $collections = StockOpname::whereIn('warehouse_id', $manager_warehouse)->get();
        }
        
        return view('stock_opnames.index', compact('collections'));
    }

    public function create() {
        $method = 'POST';
        $action = route('stock_opnames.store');
        if(Auth::user()->hasRole('WarehouseSupervisor')) {
            $warehouse = Warehouse::find(session()->get('warehouse_id'));
        } else {
            $warehouse = [];
        }
        if(!$warehouse && Auth::user()->hasRole('WarehouseSupervisor')) {
            abort(404);
        }
        return view('stock_opnames.create')->with(['action' => $action, 'method' => $method, 'warehouse' => $warehouse]);
    }

    public function store(Request $request) {
        $request->validate([
            'date_stock_opname' => 'required|date',
            'anggota' => 'required',
        ]);
        if(Auth::user()->hasRole('WarehouseSupervisor')) {
            $warehouse = Warehouse::find(session()->get('warehouse_id'));
        } else {
            $warehouse = Warehouse::find($request->warehouse);
        }

        if(!$warehouse) {
            return redirect()->back()->with('error','Warehouse tidak valid/tidak aktif');
        }

        $validate = \App\StockTransport::where('warehouse_id', $warehouse->id)
        ->where('status', 'Processed')->first();
        if($validate) {
            return redirect()->back()->with('error','Ada GR / DP yang belum di Complete!');
        }
        
        $validate = \App\StockEntry::where('warehouse_id', $warehouse->id)
        ->where('status', 'Processed')->first();
        if($validate) {
            return redirect()->back()->with('error','Ada PA / PP yang belum di Complete!');
        }

        // dd($this->managementStock());
        $stockOpname = new StockOpname;
        $stockOpname->warehouse_id = $warehouse->id;
        $stockOpname->calculated_by = json_encode($request->anggota);
        $stockOpname->date = Carbon::parse($request->date_stock_opname);
        $stockOpname->save();

        if($stockOpname->save()){
            StockOpnameDetail::insert($this->managementStock($stockOpname->id, $request));
        }

        return redirect('stock_opnames/'.$stockOpname->id.'/edit')->with('success', 'Data berhasil disimpan');
    }

    public function managementStock($id, Request $request)
    {
        $stock_ins = Warehouse::select([
            'stock_entries.warehouse_id',
            'items.id as item_id',
            'items.default_uom_id',
            'stock_entries.project_id',
            'stock_entry_details.storage_id',
            DB::raw("sum(stock_entry_details.qty) as total_in, CONCAT(stock_entry_details.warehouse_id,'_',stock_entry_details.item_id,'_', stock_entries.project_id, '_',stock_entry_details.storage_id) AS concat_id")
        ])
        ->join('parties', 'parties.id', 'warehouses.branch_id')
        ->join('stock_entries', 'stock_entries.warehouse_id', 'warehouses.id')
        ->join('projects', 'projects.id', 'stock_entries.project_id')
        ->join('stock_entry_details', 'stock_entry_details.stock_entry_id', 'stock_entries.id')
        ->join('items', 'items.id', 'stock_entry_details.item_id');
        if(Auth::user()->hasRole('WarehouseSupervisor')) {
            $stock_ins->where('stock_entries.warehouse_id', session()->get('warehouse_id'));
        } else {
            $stock_ins->where('stock_entries.warehouse_id', $request->warehouse);
        }
        $stock_ins
        ->where('warehouses.is_active', 1);
        // ->where(DB::raw('Date(stock_entry_details.created_at)'),'>=' ,$request->date_from)
        if($request->date_stock_opname != null) {
            $stock_ins
            ->where(DB::raw('Date(stock_entry_details.created_at)'),'<=' ,$request->date_stock_opname);
        }
        // ->where(DB::raw('Date(stock_entry_details.updated_at) <= NOW()'))
        $stock_ins = $stock_ins
        ->where('stock_entries.status', 'Completed')
        ->where('stock_entries.type', 'inbound');
        $stock_ins = $stock_ins->groupBy('concat_id')
        ->get();

        $stock_outs = StockEntryDetail::select([
            DB::raw("sum(stock_entry_details.qty) as total_out, CONCAT(stock_entry_details.warehouse_id,'_',stock_entry_details.item_id,'_', stock_entries.project_id, '_', stock_entry_details.storage_id) AS m_concat_id"), 'stock_entry_details.item_id'
            , 'stock_entry_details.warehouse_id', 'stock_entries.project_id', 'stock_entry_details.storage_id'
        ])
        ->join('stock_entries', 'stock_entries.id', 'stock_entry_details.stock_entry_id')
        ->where('stock_entries.status', 'Completed');
        if(Auth::user()->hasRole('WarehouseSupervisor')) {
            $stock_ins->where('stock_entries.warehouse_id', session()->get('warehouse_id'));
        } else {
            $stock_ins->where('stock_entries.warehouse_id', $request->warehouse);
        }
        $stock_outs
        ->where('stock_entries.type', 'outbound');
        if($request->date_stock_opname != null) {
            $stock_outs
            ->where(DB::raw('Date(stock_entry_details.created_at)'),'<=' ,$request->date_stock_opname);
        }
        $stock_outs = $stock_outs->groupBy('m_concat_id')->get();
        // dd($stock_outs);
        // dd($stock_ins);
        $arr_out = [];
        foreach ($stock_outs as $out) {
          $arr_out[$out->item_id."_".$out->warehouse_id."_".$out->project_id."_".$out->storage_id]=$out->total_out;
        }
        $fix_arr = [];
        foreach ($stock_ins as $in) {
            if (!empty($arr_out[$in->item_id."_".$in->warehouse_id."_".$in->project_id."_".$in->storage_id])){
                $qty_out = $arr_out[$in->item_id."_".$in->warehouse_id."_".$in->project_id."_".$in->storage_id];
            }else{
                $qty_out = 0;
            }

            $total_out = $qty_out;
            $stock = ($in->total_in) - $qty_out;
            if($stock != 0) {
                $a = [
                    'project_id'   => $in->project_id,
                    'uom_id' => $in->default_uom_id,
                    'item_id' => $in->item_id,
                    'wina_stock' => $stock,
                    'stock_opname_id' => $id,
                    'storage_id' => $in->storage_id
                ];
                array_push($fix_arr, $a);
            }
            //   $fix_arr->push($a);
              
        }
        return $fix_arr;
    }

    public function edit(StockOpname $stock_opname) {
        $action = route('stock_opnames.update', $stock_opname->id);
        $method = 'PUT';
        return view('stock_opnames.edit', compact('action', 'method', 'stock_opname'));
    }

    public function update(Request $request) {
        
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'actual_qty' => 'required',
        ]);

        $itemId = $request->input('item_id');
        $actualQty = $request->input('actual_qty');
        $description = $request->input('description');

        $stockOpname = StockOpname::where('item_id', $itemId)->first();

        if (empty($stockOpname)) {
            $stockOpname = new StockOpname;
            $stockOpname->item_id = $itemId;
            $stockOpname->actual_stock = $actualQty;
            $stockOpname->save();
        } else {
            $stockOpname->actual_stock = $actualQty;
            $stockOpname->description = $description;
            $stockOpname->save();
        }


        return redirect()->route('stock_opnames.index')->with('success', 'Data berhasil diubah');
    }

    public function print(StockOpname $stock_opname) {
    
        return view('stock_opnames.print', compact('stock_opname', $stock_opname));
    }

    public function complete(StockOpname $stock_opname, Request $request)
    {
        // dd($stock_opname);
        $stock_opname->status = 'Completed';
        $stock_opname->note = json_encode($request->keterangan);
        $stock_opname->save();

        return redirect('stock_opnames/'.$stock_opname->id.'/view')->with('success', 'Data berhasil disimpan');
    }

    public function show(StockOpname $stock_opname)
    {
        return view('stock_opnames.view', compact('stock_opname'));
    }

    public function destroy(StockOpname $stock_opname)
    {
        $stock_opname->details()->delete();
        $stock_opname->delete();

        return redirect('stock_opnames')->with('success', 'Data berhasil di hapus');
    }

    public function export($stock_opname)
    {
        // dd($stock_opname);   
        try {
            return Excel::download(new StockOpnameExport($stock_opname),'Stock Opname.xlsx');
            // return Excel::download(new ReportExport(app('App\Http\Controllers\ReportApiController')->goodreceiveManagement($request)['data'],11), 'Report Good Issue.xlsx');
        }
        catch (\Exception $e) {
            dd($e->getMessage());
        }   
    }
}
