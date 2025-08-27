<?php

namespace App\Http\Controllers;

use App\AdvanceNotice;
use App\AdvanceNoticeDetail;
use App\StockTransportDetail;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Auth;
use DB;

class AdvanceNoticeApiController extends Controller
{
    public function __construct(AdvanceNotice $advanceNotice)
    {
        $this->advanceNotice = $advanceNotice;

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

    public function dataTables($type='inbound' ,Request $request)
    {

        $collections = null;

        if(Auth::user()->branch && !Auth::user()->hasRole('CargoOwner')) {
            // 1. Kalau inbound, keluarkan data dimana consignee equals to user branch data
            $collections = AdvanceNotice::where('type',$type)
                    ->where('project_id', session()->get('current_project')->id)  
                    ->whereIn('status',['Completed', 'Closed'])
                    ->where('advance_notice_activity_id','<>', 20)
                    ->orderBy('id', 'desc')
                    ->with(['details.item', 'shipper', 'consignee', 'origin']);

            if($type == 'inbound') {
                $collections = $collections->where('consignee_id', Auth::user()->branch->id);
            } else {
                $collections = $collections->where('shipper_id', Auth::user()->branch->id);

            }

            // 2. Kalau yg login WH supervisor, di filter lg datanya berdasarkan yang di assign ke dia saja
            if(Auth::user()->hasRole('WarehouseSupervisor')) {
                $collections = $collections->where(function($query) {
                    $query->where('warehouse_id', session()->get('warehouse_id'))
                    ->orderBy('id', 'desc')
                    ->whereNotNull('employee_name');
                });
            }
    //         // $collections = $collections->limit(2000)->get();
        }

        if(Auth::user()->hasRole('CargoOwner')) {
            $collections = AdvanceNotice::where('type',$type)
                    ->where('project_id', session()->get('current_project')->id);
                    if(session()->get('current_project')->id != 361 && session()->get('current_project')->id != 177) {
                        $collections->where('user_id',Auth::user()->id);
                    }
                    $collections 
                    ->orderBy('id', 'desc')
                    ->with(['details.item', 'shipper', 'consignee', 'origin']);
        }

        $collections = $collections->limit(500)->get();

        try {
            return DataTables::of($collections)
            ->editColumn('shipper_id', function ($collection) use ($type){   
                if($type == 'inbound') {
                    return $collection->shipper->name;
                } else {
                    return $collection->consignee->name;
                }
            })
            ->editColumn('origin_id', function ($collection) {
                return $collection->origin->name;
            })
            ->editColumn('destination_id', function ($collection) {
                return $collection->destination->name;
            })
            ->addColumn('details', function($collection) use ($type) {
                $total_items = $collection->details->sum('qty');
                $delivered_items = 0;
                $transports = $collection->transports()->ofStatus(['Completed','Processed'])->get();
                foreach ($transports as $transport) {
                    if($type=='inbound'){
                        $delivered_items += $transport->details->sum('qty');
                    }
                    else{
                        $delivered_items += $transport->details->sum('plan_qty');
                    }
                }
                $collection->outstanding = $total_items - $delivered_items;
                $detail_advance_notices[$collection->id] = AdvanceNoticeDetail::where('stock_advance_notice_id',$collection->id)->get();

                foreach($detail_advance_notices[$collection->id] as $detail){
                    $inbound = AdvanceNoticeDetail::join('stock_advance_notices as san', 'san.id', '=', 'stock_advance_notice_details.stock_advance_notice_id')
                    //->where('san.status', 'Closed')
                    ->where([
                        'stock_advance_notice_details.item_id' => $detail->item_id,
                        'stock_advance_notice_details.ref_code' => $detail->ref_code,
                        'san.id' => $collection->id,
                    ])
                    ->sum('stock_advance_notice_details.qty');
                    

                    if($type == 'inbound'){
                        $outbound_completed = StockTransportDetail::join('stock_transports as st', 'st.id', '=', 'stock_transport_details.stock_transport_id')
                        ->where('st.status', 'Completed')
                        ->where([
                            'stock_transport_details.item_id' => $detail->item_id,
                            'stock_transport_details.ref_code' => $detail->ref_code,
                            'st.advance_notice_id' => $collection->id
                        ])
                        ->sum('stock_transport_details.qty');
                    
                    }
                    //ketika dp, data plan adalah actual
                    else{
                        $outbound_completed = StockTransportDetail::join('stock_transports as st', 'st.id', '=', 'stock_transport_details.stock_transport_id')
                        ->where('st.status', 'Completed')
                        ->where([
                            'stock_transport_details.item_id' => $detail->item_id,
                            'stock_transport_details.ref_code' => $detail->ref_code,
                            'st.advance_notice_id' => $collection->id
                        ])
                        ->sum('stock_transport_details.plan_qty');
                    }

                    $outbound_incompleted = StockTransportDetail::join('stock_transports as st', 'st.id', '=', 'stock_transport_details.stock_transport_id')
                        ->where('st.status', 'Processed')
                        ->where([
                            'stock_transport_details.item_id' => $detail->item_id,
                            'stock_transport_details.ref_code' => $detail->ref_code,
                            'st.advance_notice_id' => $collection->id
                        ])
                        ->sum('stock_transport_details.plan_qty');

                        //return $stockTransport->advance_notice_id;
                    $detail->detail_outstanding = $inbound - ($outbound_completed + $outbound_incompleted);
                }

                $html = '';
                $html .= '<p class="text-center">'.number_format($collection->outstanding, 2, ',', '.') .'</p>' ;

                $html .= '<p><select class="form-control"  align="center">';
                foreach($detail_advance_notices[$collection->id] as $detail) {
                    $html .= '<option>' .  $detail->item->name .' | '.  $detail->ref_code . ' | ots:'. $detail->detail_outstanding .'</option>';
                }
                $html .= '</select></p>';
                return $html;
            })
            ->addColumn('receiving', function($collection) use ($type) {
                return ($collection->outstanding == 0  && ($collection->status == 'Completed' || $collection->status == 'Closed') ? 'Full' : 'Partial');
            })
            ->addColumn('assignTo', function($collection) {
                if(Auth::user()->hasRole('WarehouseManager')){
                    if($collection->employee_name){
                        return $collection->employee_name;
                    }else{
                        return 'spv belum di assign';
                    }
                }
            })
            ->addColumn('action', function($collection) {
                $html = '';
                $html .='<div class="btn-toolbar">';
                $showUrl = route('advance_notices.show', $collection->id);
                $editUrl = route('advance_notices.edit', $collection->id);
                $delete = route('advance_notices.destroy', $collection->id);
                    if(Auth::user()->id == $collection->user_id) {
                        if($collection->editable) {
                            $html .= '<div class="btn-group" role="group">';
                            $html .= '<a href="'.$editUrl.'" type="button" class="btn btn-primary" title="Edit">
                                <i class="fa fa-pencil"></i>
                            </a></div>';
                            $html .= '<div class="btn-group" role="group"><form action="'.$delete.'" method="POST" onclick="return confirm("Are you sure you want to delete this item?");">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="'. csrf_token() .'">
                                    <button type="submit" class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i></a></button>
                                </form>
                            </div> ';
                            
                        }else{
                            $html .= '<div class="btn-group" role="group">
                                <a href="'.$showUrl.'" type="button" class="btn btn-primary" title="View">
                                    <i class="fa fa-eye"></i>
                                </a>
                            </div>';
                        }
                    }else {
                            $html .= '<div class="btn-group" role="group">
                            <a href="'.$showUrl.'" type="button" class="btn btn-primary" title="View">
                                <i class="fa fa-eye"></i>
                            </a>
                        </div>';
                        }
                       
                $html .= "</div>";
                return $html;
            })
            ->rawColumns(['details', 'action'])
            ->make(true);
        } catch (Exception $e) {
            throw new DataTablesException($e);
        }
    }
}