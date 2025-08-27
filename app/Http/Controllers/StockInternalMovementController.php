<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\InternalMovement;
use App\InternalMovementDetial;
use Auth;
use Carbon\Carbon;
use App\AdvanceNotice;
use App\AdvanceNoticeDetail;
use App\StockTransport;
use App\StockTransportDetail;
use App\StockEntry;
use App\StockEntryDetial;
use App\RoleUser;
use App\StockOpname;
use DB;

class StockInternalMovementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $internal_movements = InternalMovement::where('project_id', session()->get('current_project')->id)->where('warehouse_id',  session()->get('warehouse_id'))->get();
        // dd($internal_movements[0]->code);
        return view('stock_internal_movement.index')->with(['internal_movements' => $internal_movements]);
    }

    /**
     * Show the form for creating a new resource.
     
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $stock = StockOpname::where('warehouse_id', session()->get('warehouse_id'))->where('status', 'Processed')->first();
        if($stock) {
            return redirect()->back()->with('error','Ada Stock Opname yang belum di Complete!');
        }
        $collections = StockEntry::where('stock_entries.type', 'outbound')
        ->where('stock_entries.project_id', session()->get('current_project')->id)
        ->orderBy('stock_entries.id', 'desc')
        ->where('status', 'Processed')
        ->where('stock_entries.user_id', Auth::user()->id)->first();
    
        if($collections) {
            return redirect()->back()->with('error','Ada Picking Plan yang belum di Complete!');
        }

        $method = 'POST';
        $action = route('stock_internal_movements.store');
        $stock_internal_movement = new InternalMovement;
        // dd($stock_internal_movement);
        return view('stock_internal_movement.create')->with([
            'action' => $action,
            'method' => $method,
            'stock_internal_movement' => $stock_internal_movement,

        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $stock = StockOpname::where('warehouse_id', session()->get('warehouse_id'))->where('status', 'Processed')->first();
        if($stock) {
            return redirect()->back()->with('error','Ada Stock Opname yang belum di Complete!');
        }
        $request->validate([
            'code_reference' => 'required',
            'note' => 'required',
        ]);

        $model = new InternalMovement;
        
        $model->code_reference = $request->code_reference;
        $model->note = $request->note;
        if($request->hasFile('an_doc')) {
            $model->document = $request->an_doc->store('internal_movement', 'public');
        }
        $model->warehouse_id = session()->get('warehouse_id');
        $model->project_id = session()->get('current_project')->id;

        $model->save();
        
        $user_company_id = sprintf("%04d", session()->get('current_project')->id);
        $model_code = $model->getDocIntCode();
        $year_month = Carbon::now()->format('ym');
        $doc_id = sprintf("%04d", $model->id);
        $doc_code = $user_company_id.'.'.$model_code.'.'.$year_month.'.'.$doc_id;
        $model->code = $doc_code;
        $model->save();
        return redirect('stock_internal_movements/'.$model->id.'/edit')->with('success', 'Data berhasil disimpan');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(InternalMovement $stock_internal_movement)
    {
        return view('stock_internal_movement.view')->with(['stock_internal_movement' => $stock_internal_movement]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(InternalMovement $stock_internal_movement)
    {
        $stock = StockOpname::where('warehouse_id', session()->get('warehouse_id'))->where('status', 'Processed')->first();
        if($stock) {
            return redirect()->back()->with('error','Ada Stock Opname yang belum di Complete!');
        }
        $method = 'PUT';
        $action = route('stock_internal_movements.update', $stock_internal_movement->id);
        $details = [];
        if($stock_internal_movement->status == 'Completed')
        {
            return redirect('stock_internal_movements')->with('error', 'Data telah di Complete sebelumnya');
        }
        // dd($stock_internal_movement->detailmovement);
        return view('stock_internal_movement.edit')->with([
            'stock_internal_movement' => $stock_internal_movement,
            'action' => $action,
            'method' => $method
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(InternalMovement $stock_internal_movement)
    {
        $stock_internal_movement->details()->delete();
        $stock_internal_movement->delete();

        return redirect('stock_internal_movements')->with('success', 'Data berhasil di hapus');
    }

    public function completed(InternalMovement $internal_movement, Request $request)
    {
        // dd($stock_internal_movement);
        $request->validate([
            'password' => 'required|string',
        ]);

        $hasher = app('hash');
        if (!$hasher->check($request->input('password'), Auth::user()->password)) {
            return redirect()->back()->with('error','Password salah');
        }
        
        $now = Carbon::now();
        // dd($now->format('Y-m-d'));
        // keluarkan barang sampai GI?
        if($internal_movement->status == 'Completed')
        {
            return redirect('stock_internal_movements')->with('error', 'Data telah di Complete sebelumnya');
        }
        try {    
        
            $data = [
                'outbound', 'inbound'
            ];

            $cargoOwner = RoleUser::join('users', 'users.id', 'role_user.user_id')
                    ->where('role_user.role_id', 3)
                    ->where('branch_id', $internal_movement->warehouse->branch_id)
                    ->select('role_user.user_id')
                    ->first();
            $checker = RoleUser::join('users', 'users.id', 'role_user.user_id')
                ->where('role_user.role_id', 5)
                ->join('warehouse_officer', 'warehouse_officer.user_id', 'users.id')
                ->where('warehouse_id', $internal_movement->warehouse_id)
                ->where('branch_id', $internal_movement->warehouse->branch_id)
                ->select('users.first_name', 'users.last_name')
                ->first();
            if(!$cargoOwner || !$checker){
                return redirect()->back()->with('error','Cargo Owner / Checker tidak Valid');
            } 
            
            
            $last_name = Auth::user()->last_name != null ? ' '. Auth::user()->last_name : '';

            DB::beginTransaction();
            foreach($data as $d) {
                // dd($d);
                $model = new AdvanceNotice;
                // dd( session()->get('current_project')->id);
                
                $model->etd =  $now->format('Y-m-d');
                $model->eta = $now->format('Y-m-d');
                $model->origin_id = $internal_movement->warehouse ? $internal_movement->warehouse->city_id : '';
                $model->destination_id = $internal_movement->warehouse ? $internal_movement->warehouse->city_id : '';
                $model->ref_code = 'INTERNAL MOVEMENT - .'. $internal_movement->code;
                $model->shipper_id = $internal_movement->warehouse ? $internal_movement->warehouse->branch_id : '';
                $model->shipper_address = $internal_movement->warehouse ? $internal_movement->warehouse->branch->address : '';
                $model->consignee_id = $internal_movement->warehouse ? $internal_movement->warehouse->branch_id : '';
                $model->consignee_address = $internal_movement->warehouse ? $internal_movement->warehouse->branch->address : '';
                $model->contract_number = 'Internal Movement Contract';
                $model->spmp_number = 'SPMP internal Movement';
                $model->user_id = $cargoOwner->user_id;
                $model->user_id2 = 0;
                $model->project_id = session()->get('current_project')->id;
                $model->is_seen = 1;
                $model->status = "Completed";
                $model->employee_name = Auth::user()->first_name .''. $last_name;
                $model->is_accepted = 3;
                $model->advance_notice_activity_id = 20;
                $model->transport_type_id = 3;
                $model->warehouse_id = $internal_movement->warehouse->id;
                $model->an_doc = $internal_movement->document;
                $model->type = $d;
                $model->save();
    
                $user_company_id = sprintf("%04d", session()->get('current_project')->id);
                $model_code = $model->getDocMovementCode($model->type);
                $year_month = Carbon::now()->format('ym');
                $doc_id = sprintf("%04d", $model->id);
                $doc_code = $user_company_id.'.'.$model_code.'.'.$year_month.'.'.$doc_id;
                $model->code = $doc_code;
                $model->save();
    
                $user_company_id = sprintf("%04d", session()->get('current_project')->id);
                $model_code = $model->getDocMovementCode($model->type);
                $year_month = Carbon::now()->format('ym');
                $doc_id = sprintf("%04d", $model->id);
                $doc_code = $user_company_id.'.'.$model_code.'.'.$year_month.'.'.$doc_id;
                $model->code = $doc_code;
                $model->save();
    
                foreach($internal_movement->details as $detail) {
                    $model->details()->create([
                        'item_id' => $detail->item_id,
                        'uom_id' => $detail->movement_uom_id,
                        'qty' => $detail->movement_qty,
                        'ref_code' => $detail->ref_code,
                        'weight' => $detail->movement_weight,
                        'volume' => $detail->movement_volume
                    ]);
                }
    
                $model_transport = new StockTransport;
                $model_transport->type                = $model->type;
                $model_transport->advance_notice_id   = $model->id;
                $model_transport->transport_type_id   = 3;
                $model_transport->etd                 = $model->etd;
                $model_transport->eta                 = $model->eta;
                $model_transport->origin_id           = $model->origin_id;
                $model_transport->destination_id      = $model->destination_id;
                $model_transport->ref_code            = $model->ref_code;
                $model_transport->shipper_id          = $model->shipper_id;
                $model_transport->shipper_address     = $model->shipper_address;
                $model_transport->warehouse_id        = $model->warehouse_id;
                $model_transport->pickup_order        = $now;
                $model_transport->consignee_id        = $model->consignee_id;
                $model_transport->consignee_address   = $model->consignee_address;
                $model_transport->employee_name       = $checker->first_name .''. $checker->last_name;
                $model_transport->user_id             = Auth::user()->id;
                $model_transport->project_id          = session()->get('current_project')->id;
                $model_transport->status              = "Completed";
                $model_transport->save();
    
                foreach($internal_movement->details as $detail) {
                    $model_transport->details()->create([
                        'item_id' => $detail->item_id,
                        'uom_id' => $detail->movement_uom_id,
                        'plan_qty' => $d == 'outbound' ? $detail->movement_qty : 0,
                        'ref_code' => $detail->ref_code,
                        'plan_weight' => $d == 'outbound' ? $detail->movement_weight : 0,
                        'plan_volume' => $d == 'outbound' ? $detail->movement_volume : 0,
                        'control_date' => $detail->control_date,
                        'qty' => $d == 'inbound' ? $detail->movement_qty : 0,
                        'weight' => $d == 'inbound' ? $detail->movement_weight : 0,
                        'volume' => $d == 'inbound' ? $detail->movement_volume : 0,
                    ]);
                }
    
                $model_code_transport = $model_transport->getDocCode($model_transport->type);
                $doc_id_transport = sprintf("%04d", $model_transport->id);
                $doc_code_transport = $user_company_id.'.'.$model_code_transport.'.'.$year_month.'.'.$doc_id_transport;
                $model_transport->code = $doc_code_transport;
                $model_transport->save();
    
                $model_entry = new StockEntry;
                $model_entry->type = $model->type;
                $model_entry->stock_transport_id = $model_transport->id;
                $model_entry->total_pallet = 0;
                $model_entry->total_labor = 0;
                $model_entry->total_forklift = 0;
                $model_entry->forklift_start_time = $now;
                $model_entry->forklift_end_time = $now;
                $model_entry->ref_code = $model->ref_code;
                $model_entry->employee_name = $checker->first_name .''. $checker->last_name;
                $model_entry->warehouse_id = $model->warehouse_id;
                $model_entry->status = 'Completed';
                $model_entry->user_id = Auth::user()->id;
                $model_entry->project_id = session()->get('current_project')->id;
                $model_entry->save();
    
                foreach($internal_movement->details as $detail) {
                    $model_entry->details()->create([
                        'item_id' => $detail->item_id,
                        'warehouse_id' => $detail->warehouse_id,
                        'storage_id' => $d == 'outbound' ? $detail->origin_storage_id : $detail->dest_storage_id,
                        'uom_id' => $detail->movement_uom_id,
                        'control_date' => $detail->control_date,
                        'weight' => $detail->movement_weight,
                        'volume' => $detail->movement_volume,
                        'qty' => $detail->movement_qty,
                        'status' => 'draft',
                        'ref_code' => $detail->ref_code,
                        'begining_qty' => 0,
                        'ending_qty' => 0,
                    ]);
                }
    
                $model_code_entry = $model_entry->getDocCode($model_entry->type);
                $doc_id_entry = sprintf("%04d", $model_entry->id);
                $doc_code_entry = $user_company_id.'.'.$model_code_entry.'.'.$year_month.'.'.$doc_id_entry;
                $model_entry->code = $doc_code_entry;
                $model_entry->save();
            }
            $internal_movement->status = 'Completed';
            $internal_movement->save();
            DB::commit();
            return redirect('stock_internal_movements')->with('success', 'Data berhasil di Completed!');
        } 
        catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->input())->with('error', 'Terjadi kesalahan saat menyimpan data. Silahkan ulangi kembali ' . $e->getMessage());
        }

    }
}
