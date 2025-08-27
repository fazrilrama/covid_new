<?php

namespace App\Http\Controllers;

use App\AdvanceNoticeDetail;
use App\Region;
use App\StockDelivery;
use App\StockDeliveryDetail;
use App\StockTransportDetail;
use App\Warehouse;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Auth;
use Spatie\Activitylog\Models\Activity;
use App\User;
use App\AdvanceNotice;
use App\StockTransport;
use App\StockEntry;
use App\StockEntryDetail;
use App\Project;
use App\Storage;
use App\Commodity;
use DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (empty(session()->get('current_project'))) {
            return redirect('empty-project');
        }
        $projectId = session()->get('current_project')->id;

        $advance_notices = AdvanceNotice::where('project_id',$projectId);
        if(Auth::user()->hasRole('WarehouseSupervisor')) {
            $advance_notices->where('warehouse_id',session()->get('warehouse_id'));
        }
        $advance_notices = $advance_notices->get();

        $stock_transports = StockTransport::where('project_id',$projectId);
        if(Auth::user()->hasRole('WarehouseSupervisor')) {
            $stock_transports->where('warehouse_id',session()->get('warehouse_id'));
        }
        $stock_transports = $stock_transports->get();

        $handlingIns = [];
        $handlingOuts = [];
        for ($i=-4; $i <= 0 ; $i++) {
            $now = Carbon::now()->addDays($i)->format('Y-m');
            $label = Carbon::now()->addDays($i)->format('d-M-Y');

            $handlingInCount = StockEntryDetail::join('stock_entries as se', 'se.id', '=', 'stock_entry_details.stock_entry_id')
                ->where(DB::raw('DATE_FORMAT(DATE(stock_entry_details.created_at), "%Y-%m")'), $now)
                ->where([
                    'se.type' => 'inbound',
                    'se.project_id' => $projectId,
                ]);
                if(Auth::user()->hasRole('WarehouseSupervisor')) {
                    $handlingInCount->where('se.warehouse_id',session()->get('warehouse_id'));
                }
                $handlingInCount = $handlingInCount
                ->groupBy(DB::raw('DATE_FORMAT(DATE(stock_entry_details.created_at), "%Y-%m")'))
                ->sum('stock_entry_details.weight');

            array_push($handlingIns, [
                'label' => $label,
                'y' => floatval($handlingInCount),
            ]);

            $handlingOutCount = StockEntryDetail::join('stock_entries as se', 'se.id', '=', 'stock_entry_details.stock_entry_id')
                ->where(DB::raw('DATE_FORMAT(DATE(stock_entry_details.created_at), "%Y-%m")'), $now)
                ->where([
                    'se.type' => 'outbound',
                    'se.project_id' => $projectId,
                ]);
                if(Auth::user()->hasRole('WarehouseSupervisor')) {
                    $handlingOutCount->where('se.warehouse_id',session()->get('warehouse_id'));
                }
                $handlingOutCount = $handlingOutCount
                ->groupBy(DB::raw('DATE_FORMAT(DATE(stock_entry_details.created_at), "%Y-%m")'))
                ->sum('stock_entry_details.weight');

            array_push($handlingOuts, [
                'label' => $label,
                'y' => floatval($handlingOutCount),
            ]);
        }

        //        INBOUND OUTBOUND ====================================


        $ain = [];
        $gr = [];
        $pa = [];
        $pendingInbound = [];
        $aon = [];
        $dp = [];
        $pp = [];
        $gi = [];
        $pendingOutbound = [];

        $totalRequested = 0;
        $totalProcessed = 0;
        $totalCompleted = 0;
        for ($i=-4; $i <= 0 ; $i++) {
            $now = Carbon::now()->addDays($i)->format('Y-m-d');
            $label = Carbon::now()->addDays($i)->format('d-M-Y');

            //--------------INBOUND
            //AIN
            $ainCount = AdvanceNotice::select('code')
                ->where(DB::raw('DATE_FORMAT(DATE(stock_advance_notices.created_at), "%Y-%m-%d")'), '=', $now)
                ->where('project_id', $projectId)
                ->where('type', 'inbound')
                ->where('status', 'Completed');
                if(Auth::user()->hasRole('WarehouseSupervisor')) {
                    $ainCount->where('stock_advance_notices.warehouse_id',session()->get('warehouse_id'));
                }
                $ainCount = $ainCount->count();
            array_push($ain, [
                'label' => $label,
                'y' => floatval($ainCount),
            ]);

            //GR
            $grCount = StockTransport::select('code')->where(DB::raw('DATE_FORMAT(DATE(stock_transports.created_at), "%Y-%m-%d")'), $now)
                ->where('project_id', $projectId)
                ->where('type', 'inbound')
                ->where('status', 'Completed')
                ->groupBy(DB::raw('DATE_FORMAT(DATE(stock_transports.created_at), "%Y-%m-%d")'));
                if(Auth::user()->hasRole('WarehouseSupervisor')) {
                    $grCount->where('stock_transports.warehouse_id',session()->get('warehouse_id'));
                }
                $grCount = $grCount->count();
            array_push($gr, [
                'label' => $label,
                'y' => floatval($grCount),
            ]);

            //PA
            $paCount = StockEntry::select('code')->where(DB::raw('DATE_FORMAT(DATE(stock_entries.created_at), "%Y-%m-%d")'), $now)
                ->where('project_id', $projectId)
                ->where('type', 'inbound')
                ->where('status', 'Completed');
                if(Auth::user()->hasRole('WarehouseSupervisor')) {
                    $paCount->where('stock_entries.warehouse_id',session()->get('warehouse_id'));
                }
                $paCount = $paCount
                ->groupBy(DB::raw('DATE_FORMAT(DATE(stock_entries.created_at), "%Y-%m-%d")'))
                ->count();
            array_push($pa, [
                'label' => $label,
                'y' => floatval($paCount),
            ]);

            //AIN PENDING
            $ainPendingCount = AdvanceNotice::select('code')->where(DB::raw('DATE_FORMAT(DATE(stock_advance_notices.created_at), "%Y-%m-%d")'), $now)
                ->where('project_id', $projectId)
                ->where('type', 'inbound')
                ->where('status', 'Pending');
                if(Auth::user()->hasRole('WarehouseSupervisor')) {
                    $ainPendingCount->where('stock_advance_notices.warehouse_id',session()->get('warehouse_id'));
                }
                $ainPendingCount = $ainPendingCount
                ->groupBy(DB::raw('DATE_FORMAT(DATE(stock_advance_notices.created_at), "%Y-%m-%d")'));

            //GR PENDING
            $grPendingCount = StockTransport::select('code')->where(DB::raw('DATE_FORMAT(DATE(stock_transports.created_at), "%Y-%m-%d")'), $now)
                ->where('project_id', $projectId)
                ->where('type', 'inbound')
                ->where('status', 'Pending');
                if(Auth::user()->hasRole('WarehouseSupervisor')) {
                    $grPendingCount->where('stock_transports.warehouse_id',session()->get('warehouse_id'));
                }
                $grPendingCount = $grPendingCount
                ->groupBy(DB::raw('DATE_FORMAT(DATE(stock_transports.created_at), "%Y-%m-%d")'));

            //INBOUND PENDING
            /* $inboundPendingCount = StockEntry::select('code')
                ->where(DB::raw('DATE_FORMAT(DATE(stock_entries.created_at), "%Y-%m-%d")'), $now)
                ->where('project_id', $projectId)
                ->where('type', 'inbound')
                ->where('status', 'Pending')
                ->groupBy(DB::raw('DATE_FORMAT(DATE(stock_entries.created_at), "%Y-%m-%d")'))
                ->unionAll($ainPendingCount)
                ->unionAll($grPendingCount)
                ->count();
                array_push($pendingInbound, [
                    'label' => $label,
                    'y' => floatval($inboundPendingCount),
                ]); */
            $pendingAin = AdvanceNotice::where([
                'type' => 'inbound',
                'status' => 'pending',
            ])
            ->where(DB::raw('DATE_FORMAT(DATE(created_at), "%Y-%m-%d")'), $now);
            if(Auth::user()->hasRole('WarehouseSupervisor')) {
                $pendingAin->where('warehouse_id',session()->get('warehouse_id'));
            }
            $pendingAin = $pendingAin
            ->count();
            $pendingGR = StockTransport::where([
                'type' => 'inbound',
                'status' => 'pending',
            ])
            ->where(DB::raw('DATE_FORMAT(DATE(created_at), "%Y-%m-%d")'), $now);
            if(Auth::user()->hasRole('WarehouseSupervisor')) {
                $pendingGR->where('stock_transports.warehouse_id',session()->get('warehouse_id'));
            }
            $pendingGR = $pendingGR
            ->count();
            $pendingPA = StockEntry::where([
                'type' => 'inbound',
                'status' => 'pending',
            ])
            ->where(DB::raw('DATE_FORMAT(DATE(stock_entries.created_at), "%Y-%m-%d")'), $now);
            if(Auth::user()->hasRole('WarehouseSupervisor')) {
                $pendingPA->where('warehouse_id',session()->get('warehouse_id'));
            }
            $pendingPA = $pendingPA
            ->count();
            $inboundPendingCount = $pendingAin + $pendingGR + $pendingPA;
            /* $inboundPendingCount = StockEntry::select('code')
                ->where(DB::raw('DATE_FORMAT(DATE(stock_entries.created_at), "%Y-%m-%d")'), $now)
                ->where('project_id', $projectId)
                ->where('type', 'inbound')
                ->where('status', 'Pending')
                ->groupBy(DB::raw('DATE_FORMAT(DATE(stock_entries.created_at), "%Y-%m-%d")'))
                ->count();
                array_push($pendingInbound, [
                    'label' => $label,
                    'y' => floatval($inboundPendingCount),
                ]); */

                array_push($pendingInbound, [
                    'label' => $label,
                    'y' => floatval($inboundPendingCount),
                ]);

            //--------------OUTBOUND
            //AON
            $aonCount = AdvanceNotice::select('code')->where(DB::raw('DATE_FORMAT(DATE(stock_advance_notices.created_at), "%Y-%m-%d")'), $now)
                ->where('project_id', $projectId)
                ->where('type', 'outbound')
                ->where('status', 'Completed');
                if(Auth::user()->hasRole('WarehouseSupervisor')) {
                    $aonCount->where('stock_advance_notices.warehouse_id',session()->get('warehouse_id'));
                }
                $aonCount = $aonCount
                ->groupBy(DB::raw('DATE_FORMAT(DATE(stock_advance_notices.created_at), "%Y-%m-%d")'))
                ->count();
            array_push($aon, [
                'label' => $label,
                'y' => floatval($aonCount),
            ]);

            //DP
            $dpCount = StockTransport::select('code')->where(DB::raw('DATE_FORMAT(DATE(stock_transports.created_at), "%Y-%m-%d")'), $now)
                ->where('project_id', $projectId)
                ->where('type', 'outbound')
                ->where('status', 'Completed');
                if(Auth::user()->hasRole('WarehouseSupervisor')) {
                    $dpCount->where('stock_transports.warehouse_id',session()->get('warehouse_id'));
                }
                $dpCount = $dpCount
                ->groupBy(DB::raw('DATE_FORMAT(DATE(stock_transports.created_at), "%Y-%m-%d")'))
                ->count();
            array_push($dp, [
                'label' => $label,
                'y' => floatval($dpCount),
            ]);

            //PP
            $ppCount = StockEntry::select('code')->where(DB::raw('DATE_FORMAT(DATE(stock_entries.created_at), "%Y-%m-%d")'), $now)
                ->where('project_id', $projectId)
                    ->where('type', 'outbound')
                    ->where('status', 'Completed');
                    if(Auth::user()->hasRole('WarehouseSupervisor')) {
                        $ppCount->where('stock_entries.warehouse_id',session()->get('warehouse_id'));
                    }
                    $ppCount = $ppCount
                        ->groupBy(DB::raw('DATE_FORMAT(DATE(stock_entries.created_at), "%Y-%m-%d")'))
                        ->count();
            array_push($pp, [
                'label' => $label,
                'y' => floatval($ppCount),
            ]);

            //GI
            $giCount = StockDelivery::select('code')->where(DB::raw('DATE_FORMAT(DATE(stock_deliveries.created_at), "%Y-%m-%d")'), $now)
                ->join('stock_entries', 'stock_entries.id', 'stock_deliveries.stock_entry_id')
                ->where('stock_entries.project_id', $projectId)
                ->where('stock_deliveries.type', 'outbound')
                ->where('stock_deliveries.status', 'Completed');
                if(Auth::user()->hasRole('WarehouseSupervisor')) {
                    $giCount->where('stock_entries.warehouse_id',session()->get('warehouse_id'));
                }
                $giCount = $giCount
                ->groupBy(DB::raw('DATE_FORMAT(DATE(stock_deliveries.created_at), "%Y-%m-%d")'))
            ->count();
            array_push($gi, [
                'label' => $label,
                'y' => floatval($giCount),
            ]);

            //AON PENDING
            $aonPendingCount = AdvanceNotice::select('code')->where(DB::raw('DATE_FORMAT(DATE(stock_advance_notices.created_at), "%Y-%m-%d")'), $now)
                ->where('project_id', $projectId)
                ->where('type', 'outbound')
                ->where('status', 'Pending');
                if(Auth::user()->hasRole('WarehouseSupervisor')) {
                    $aonPendingCount->where('stock_advance_notices.warehouse_id',session()->get('warehouse_id'));
                }
                $aonPendingCount = $aonPendingCount
                ->groupBy(DB::raw('DATE_FORMAT(DATE(stock_advance_notices.created_at), "%Y-%m-%d")'));

            //PP PENDING
            $ppPendingCount = StockEntry::select('code')->where(DB::raw('DATE_FORMAT(DATE(stock_entries.created_at), "%Y-%m-%d")'), $now)
                ->where('project_id', $projectId)
                ->where('type', 'outbound')
                ->where('status', 'Pending');
                if(Auth::user()->hasRole('WarehouseSupervisor')) {
                    $ppPendingCount->where('stock_entries.warehouse_id',session()->get('warehouse_id'));
                }
                $ppPendingCount = $ppPendingCount
                ->groupBy(DB::raw('DATE_FORMAT(DATE(stock_entries.created_at), "%Y-%m-%d")'));


            //GI PENDING
            $giPendingCount = StockDelivery::select('code')->where(DB::raw('DATE_FORMAT(DATE(stock_deliveries.created_at), "%Y-%m-%d")'), $now)
                ->join('stock_entries', 'stock_entries.id', 'stock_deliveries.stock_entry_id')
                ->where('stock_entries.project_id', $projectId)
                ->where('stock_deliveries.type', 'outbound')
                ->where('stock_deliveries.status', 'Pending');
                if(Auth::user()->hasRole('WarehouseSupervisor')) {
                    $giPendingCount->where('stock_entries.warehouse_id',session()->get('warehouse_id'));
                }
                $giPendingCount = $giPendingCount
                ->groupBy(DB::raw('DATE_FORMAT(DATE(stock_deliveries.created_at), "%Y-%m-%d")'));


            //OUTBOUND PENDING
            $pendingAon = AdvanceNotice::where([
                'type' => 'inbound',
                'status' => 'pending',
            ])
            ->where(DB::raw('DATE_FORMAT(DATE(created_at), "%Y-%m-%d")'), $now);
            if(Auth::user()->hasRole('WarehouseSupervisor')) {
                $pendingAon->where('warehouse_id',session()->get('warehouse_id'));
            }
            $pendingAon = $pendingAon
            ->count();
            $pendingDP = StockTransport::where([
                'type' => 'inbound',
                'status' => 'pending',
            ])
            ->where(DB::raw('DATE_FORMAT(DATE(created_at), "%Y-%m-%d")'), $now);
            if(Auth::user()->hasRole('WarehouseSupervisor')) {
                $pendingDP->where('warehouse_id',session()->get('warehouse_id'));
            }
            $pendingDP = $pendingDP
            ->count();
            $pendingPP = StockEntry::where([
                'type' => 'inbound',
                'status' => 'pending',
            ])
            ->where(DB::raw('DATE_FORMAT(DATE(stock_entries.created_at), "%Y-%m-%d")'), $now);
            if(Auth::user()->hasRole('WarehouseSupervisor')) {
                $pendingPP->where('stock_entries.warehouse_id',session()->get('warehouse_id'));
            }
            $pendingPP = $pendingPP
            ->count();
            $outboundPendingCount = $pendingAon + $pendingDP + $pendingPP;
            /* $outboundPendingCount = StockTransport::select('code')->where(DB::raw('DATE_FORMAT(DATE(stock_transports.created_at), "%Y-%m-%d")'), $now)
                ->where('project_id', $projectId)
                ->where('type', 'outbound')
                ->where('status', 'Pending')
                ->groupBy(DB::raw('DATE_FORMAT(DATE(stock_transports.created_at), "%Y-%m-%d")'))
                ->unionAll($aonPendingCount)
                ->unionAll($ppPendingCount)
                ->unionAll($giPendingCount)
                ->count(); */
            array_push($pendingOutbound, [
                'label' => $label,
                'y' => floatval($outboundPendingCount),
            ]);

            $totalRequested += abs($ainCount + $aonCount);
            $totalProcessed += abs($grCount + $dpCount + $ppCount);
            $totalCompleted += abs($paCount + $giCount);
        }

        //        PROGRESS ====================================

        $reqCount = 0;
        $procCount = 0;
        $compCount = 0;
        $progress = [
            [
                'y' => floatval($reqCount),
                'label' => 'Requested',
            ],
            [
                'y' => floatval($procCount),
                'label' => 'Processed',
            ],
            [
                'y' => floatval($compCount),
                'label' => 'Completed',
            ],
        ];

        //        HANDLING ====================================

        $ainUnit = [];
        $aonUnit = [];

        $handlingUnit = [];
        $handlingVolume = [];
        $handlingWeight = [];

        for ($i=-2; $i <= 0 ; $i++) {
            $now = Carbon::now()->addDays($i)->format('Y-m-d');
            $label = Carbon::now()->addDays($i)->format('d-M-Y');

            $unitInCount = StockTransportDetail::join('stock_transports as st', 'st.id', '=', 'stock_transport_details.stock_transport_id')
                ->where(DB::raw('DATE_FORMAT(DATE(st.created_at), "%Y-%m-%d")'), $now)
                ->where('project_id', $projectId)
                ->where('type', 'inbound')
                ->where('status', 'Completed');
                if(Auth::user()->hasRole('WarehouseSupervisor')) {
                    $unitInCount->where('st.warehouse_id',session()->get('warehouse_id'));
                }
                $unitInCount = $unitInCount
                ->groupBy(DB::raw('DATE_FORMAT(DATE(st.created_at), "%Y-%m-%d")'))
                ->sum('weight');
            array_push($handlingUnit, [
                'label' =>'IN' . $label,
                'y' => floatval($unitInCount),
            ]);

            $volumeInCount = StockTransportDetail::join('stock_transports as st', 'st.id', '=', 'stock_transport_details.stock_transport_id')
                ->where(DB::raw('DATE_FORMAT(DATE(st.created_at), "%Y-%m-%d")'), $now)
                ->where('project_id', $projectId)
                ->where('type', 'inbound')
                ->where('status', 'Completed');
                if(Auth::user()->hasRole('WarehouseSupervisor')) {
                    $volumeInCount->where('st.warehouse_id',session()->get('warehouse_id'));
                };
                $volumeInCount = $volumeInCount
                ->sum('volume');
            array_push($handlingVolume, [
                'label' =>'IN' . $label,
                'y' => floatval($volumeInCount),
            ]);

            $weightInCount = StockTransportDetail::join('stock_transports as st', 'st.id', '=', 'stock_transport_details.stock_transport_id')
                ->where(DB::raw('DATE_FORMAT(DATE(st.created_at), "%Y-%m-%d")'), $now)
                ->where('project_id', $projectId)
                ->where('type', 'inbound')
                ->where('status', 'Completed');
                if(Auth::user()->hasRole('WarehouseSupervisor')) {
                    $weightInCount->where('st.warehouse_id',session()->get('warehouse_id'));
                };
                $weightInCount = $weightInCount
                ->sum('qty');
            array_push($handlingWeight, [
                'label' =>'IN' . $label,
                'y' => floatval($weightInCount),
            ]);

            $unitInCount = StockDeliveryDetail::join('stock_deliveries as sd', 'sd.id', '=', 'stock_delivery_details.stock_delivery_id')
                ->join('stock_entries','stock_entries.id', 'sd.stock_entry_id')
                ->where(DB::raw('DATE_FORMAT(DATE(sd.created_at), "%Y-%m-%d")'), $now)
                ->where('sd.project_id', $projectId)
                ->where('sd.type', 'outbound')
                ->where('sd.status', 'Completed');
                if(Auth::user()->hasRole('WarehouseSupervisor')) {
                    $unitInCount->where('stock_entries.warehouse_id',session()->get('warehouse_id'));
                };
                $unitInCount = $unitInCount
                ->groupBy(DB::raw('DATE_FORMAT(DATE(sd.created_at), "%Y-%m-%d")'))
                ->sum('weight');
            array_push($handlingUnit, [
                'label' =>'OUT' . $label,
                'y' => floatval($unitInCount),
            ]);

            $volumeInCount = StockDeliveryDetail::join('stock_deliveries as sd', 'sd.id', '=', 'stock_delivery_details.stock_delivery_id')
                ->join('stock_entries','stock_entries.id', 'sd.stock_entry_id')
                ->where(DB::raw('DATE_FORMAT(DATE(sd.created_at), "%Y-%m-%d")'), $now)
                ->where('stock_entries.project_id', $projectId)
                ->where('stock_entries.type', 'outbound')
                ->where('sd.status', 'Completed');
                if(Auth::user()->hasRole('WarehouseSupervisor')) {
                    $volumeInCount->where('stock_entries.warehouse_id',session()->get('warehouse_id'));
                };
                $volumeInCount = $volumeInCount
                ->sum('volume');
            array_push($handlingVolume, [
                'label' =>'OUT' . $label,
                'y' => floatval($volumeInCount),
            ]);

            $weightInCount = StockDeliveryDetail::join('stock_deliveries as sd', 'sd.id', '=', 'stock_delivery_details.stock_delivery_id')
                ->join('stock_entries','stock_entries.id', 'sd.stock_entry_id')
                ->where(DB::raw('DATE_FORMAT(DATE(sd.created_at), "%Y-%m-%d")'), $now)
                ->where('sd.project_id', $projectId)
                ->where('sd.type', 'outbound')
                ->where('sd.status', 'Completed');
                if(Auth::user()->hasRole('WarehouseSupervisor')) {
                    $weightInCount->where('stock_entries.warehouse_id',session()->get('warehouse_id'));
                };
                $weightInCount = $weightInCount
                ->sum('qty');
            array_push($handlingWeight, [
                'label' =>'OUT' . $label,
                'y' => floatval($weightInCount),
            ]);
        }

        //        MOVEMENT MONITORING ====================================

        // $movain = DB::table('stock_advance_notices')
        // ->join('warehouses', 'warehouses.id', 'stock_advance_notices.warehouse_id')
        //     ->where('type', 'inbound')
        //     ->where('project_id', $projectId)
        //     ->where('stock_advance_notices.status', 'Completed')
        //     ->select([
        //         'stock_advance_notices.code',
        //         'stock_advance_notices.type',
        //         'stock_advance_notices.created_at',
        //         'stock_advance_notices.updated_at',
        //         'warehouses.name as warehouse_name',
        //         DB::raw("TIMESTAMPDIFF(MINUTE, stock_advance_notices.created_at, stock_advance_notices.updated_at) as times_spent"),
        //         DB::raw('"ain" as type_doc')
        //     ]);

        // $movaon = DB::table('stock_advance_notices')
        //     ->join('warehouses', 'warehouses.id', 'stock_advance_notices.warehouse_id')

        //     ->where('type', 'outbound')
        //     ->where('project_id', $projectId)
        //     ->where('stock_advance_notices.status', 'Completed')
        //     ->select([
        //         'stock_advance_notices.code',
        //         'stock_advance_notices.type',
        //         'stock_advance_notices.created_at',
        //         'stock_advance_notices.updated_at',
        //         'warehouses.name as warehouse_name',
        //         DB::raw("TIMESTAMPDIFF(MINUTE, stock_advance_notices.created_at, stock_advance_notices.updated_at) as times_spent"),
        //         DB::raw('"aon" as type_doc')
        //     ]);

        // $movgr = DB::table('stock_transports')
        // ->join('warehouses', 'warehouses.id', 'stock_transports.warehouse_id')
        //     ->where('type', 'inbound')
        //     ->where('project_id', $projectId)
        //     ->where('stock_transports.status', 'Completed')
        //     ->select([
        //         'stock_transports.code',
        //         'stock_transports.type',
        //         'stock_transports.created_at',
        //         'stock_transports.updated_at',
        //         'warehouses.name as warehouse_name',
        //         DB::raw("TIMESTAMPDIFF(MINUTE, stock_transports.created_at, stock_transports.updated_at) as times_spent"),
        //         DB::raw('"gr" as type_doc')
        //     ]);

        // $movdp = DB::table('stock_transports')
        // ->join('warehouses', 'warehouses.id', 'stock_transports.warehouse_id')
        //     ->where('type', 'outbound')
        //     ->where('project_id', $projectId)
        //     ->where('stock_transports.status', 'Completed')
        //     ->select([
        //         'stock_transports.code',
        //         'stock_transports.type',
        //         'stock_transports.created_at',
        //         'stock_transports.updated_at',
        //         'warehouses.name as warehouse_name',
        //         DB::raw("TIMESTAMPDIFF(MINUTE, stock_transports.created_at, stock_transports.updated_at) as times_spent"),
        //         DB::raw('"dp" as type_doc')
        //     ]);

        $movpa = StockEntry::join('warehouses', 'warehouses.id', 'stock_entries.warehouse_id')
            ->where('stock_entries.type', 'inbound')
            ->where('stock_entries.project_id', $projectId)
            ->where('stock_entries.status', 'Completed')
            ->select([
                'stock_entries.code',
                'stock_entries.type',
                'stock_transport_id',
                'stock_entries.created_at',
                'stock_entries.updated_at',
                'warehouses.name as warehouse_name',
                DB::raw("TIMESTAMPDIFF(MINUTE, stock_entries.created_at, stock_entries.updated_at) as times_spent"),
                DB::raw('"pa" as type_doc')
            ]);

        if(Auth::user()->hasRole('WarehouseSupervisor')) {
            $movpa->where('stock_entries.warehouse_id', session()->get('warehouse_id'));
        }
        $movpa = $movpa
            ->with(['stock_transport'])
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();
        // dd($movpa);
        // $movpp = DB::table('stock_entries')
        //     ->join('warehouses', 'warehouses.id', 'stock_entries.warehouse_id')

        //     ->where('type', 'outbound')
        //     ->where('project_id', $projectId)
        //     ->where('stock_entries.status', 'Completed')
        //     ->select([
        //         'stock_entries.code',
        //         'stock_entries.type',
        //         'stock_entries.created_at',
        //         'stock_entries.updated_at',
        //         'warehouses.name as warehouse_name',
        //         DB::raw("TIMESTAMPDIFF(MINUTE, stock_entries.created_at, stock_entries.updated_at) as times_spent"),
        //         DB::raw('"pp" as type_doc')
        //     ]);
        $stockDeliveries = StockDelivery::join('stock_entries', 'stock_entries.id', 'stock_deliveries.stock_entry_id')
            ->join('warehouses', 'warehouses.id', 'stock_entries.warehouse_id')
            ->where('stock_deliveries.project_id', $projectId)
            ->where('stock_deliveries.status', 'Completed')
            ->select([
                'stock_deliveries.code',
                'stock_deliveries.type',
                'stock_deliveries.stock_entry_id',
                'stock_deliveries.created_at',
                'stock_deliveries.updated_at',
                'warehouses.name as warehouse_name',
                DB::raw("TIMESTAMPDIFF(MINUTE, stock_deliveries.created_at, stock_deliveries.updated_at) as times_spent"),
                DB::raw('"gi" as type_doc')
            ]);
        if(Auth::user()->hasRole('WarehouseSupervisor')) {
            $stockDeliveries->where('stock_entries.warehouse_id', session()->get('warehouse_id'));
        }

        $stockDeliveries = $stockDeliveries
            ->with(['stock_entry'])
            // ->unionAll($movaon)
            // // ->unionAll($movgr)
            // ->unionAll($movdp)
            // // ->unionAll($movpa)
            // ->unionAll($movpp)
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();


            //return $stockDeliveries;

//        $value = '100000';
//        $dt = Carbon::now();
//        $days = $dt->diffInDays($dt->copy()->addSeconds($value));
//        $hours = $dt->diffInHours($dt->copy()->addSeconds($value)->subDays($days));
//        dd(CarbonInterval::days($days)->hours($hours)->forHumans(), $days, $hours);

        $projects = [];
        if(Auth::user()->hasRole('WarehouseSupervisor')) {
            $projects = Auth::user()->projects;
        }
        // dd($stockDeliveries);
        return view('home',compact(
            'advance_notices',
            'stock_transports',
            'handlingIns',
            'handlingOuts',
            'progress',
            'projects',
            'stockDeliveries',
            'movpa',
            'ain',
            'aon',
            'gr',
            'dp',
            'pp',
            'pa',
            'gi',
            'ainCount',
            'grCount',
            'aonCount',
            'ppCount',
            'paCount',
            'giCount',
            'pendingInbound',
            'pendingOutbound',
            'handlingUnit',
            'handlingVolume',
            'handlingWeight'
        ));
    }

    public function profile()
    {
        $user = auth()->user();
        return view('profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        //Validate
        $request->validate([
            'first_name' => 'required',
            'mobile_number' => 'min:10|numeric',
            'email' => 'required|email|unique:users,email,'.Auth::user()->id,
            'work_position' => 'required'
        ]);

        $model = Auth::user();
        $model->first_name = $request->get('first_name');
        $model->last_name = $request->get('last_name');
        $model->mobile_number = $request->get('mobile_number');
        $model->email = $request->get('email');
        $model->work_position = $request->get('work_position');

        if($request->filled('password')) {
            $request->validate([
                'password' => 'string|min:8|max:12|confirmed|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,12}$/'
            ]);
            $model->password = bcrypt($request->get('password'));
        }

        $model->save();
        return redirect()->route('profile')->with('success', 'Data berhasil disimpan');
    }

    public function activity_logs(Request $request)
    {
        $collections = Activity::limit(1000);
        foreach ($collections as $item) {
            if (!empty($item->causer_type)) {
                $user = $item->causer_type::find($item->causer_id);
                $item->causer = $user;

                if ($item->subject_type == 'App\AdvanceNotice' || $item->subject_type == 'App\StockTransport' || $item->subject_type == 'App\StockEntry' || $item->subject_type == 'App\StockDelivery') {
                    if($item->description == 'created' || $item->description == 'updated' || $item->description == 'deleted') {
                        if (!empty($item->properties->status)) {
                            if (!empty($item->properties->type)) {
                                if ($item->subject_type == 'App\AdvanceNotice'){
                                    $docType1 = 'AIN';
                                    $docType2 = 'AON';
                                }else if ($item->subject_type == 'App\StockTransport') {
                                    $docType1 = 'Good Receiving';
                                    $docType2 = 'Delivery Plan';
                                }else if ($item->subject_type == 'App\StockEntry') {
                                    $docType1 = 'Put Away';
                                    $docType2 = 'Picking Plan';
                                }
                                $item->description = $item->properties->status . ($item->properties->type == 'inbound' ? $docType1 : $docType2);
                            }
                        }
                    }
                }
            }
        }
        return view('activity_logs',compact('collections'));
    }

    public function setCurrentProject($id)
    {
        $project = Project::findOrFail($id);
        $user_projects = Auth::user()->projects;
        if($user_projects->contains('id',$id)) {
            session(['projects'=>Auth::user()->projects]);
            session(['current_project'=>$project]);
            // dd(session()->get('current_project')->name);
            //return redirect()->back()->with('success','Project berhasil diganti');
            // return redirect()->route('home')->with('success','Project berhasil diganti');
            if(Auth::user()->hasRole('Admin-BGR') || Auth::user()->hasRole('Admin') ||  Auth::user()->hasRole('Admin-Client') || Auth::user()->hasRole('CommandCenter')) {
                return redirect()->route('projects.index')->with('success','Project berhasil diganti');
            } elseif(Auth::user()->hasRole('WarehouseChecker') || Auth::user()->hasRole('WarehouseOfficer')) {
                return redirect('/stock_transports/inbound')->with('success','Project berhasil diganti');
            } 
            elseif(Auth::user()->hasRole('Transporter')){
                return redirect('/deliveries')->with('success','Project berhasil diganti');
            }else {
                return redirect('/advance_notices/inbound')->with('success','Project berhasil diganti');
            }
        } else {
            return redirect()->route('home')->with('error','Anda tidak mempunyai akses');
        }
    }

    public function workOrder() {
	set_time_limit(0);
        // 1. Cek branch data if exists
        if($branch = Auth::user()->branch) {
            // Kalau inbound, keluarkan data yang consignee = branch
            $inbounds = AdvanceNotice::where('type', 'inbound')
                ->where('consignee_id', $branch->id)
                ->where('status', 'Completed')
                ->where('project_id', ['334','333','332', '336'])
                ->orderBy('created_at', 'desc')
                ->get();
            // dd($inbounds);
            // Kalau outbound, keluarkan data yang shipper = branch
            $outbounds = AdvanceNotice::where('type', 'outbound')
                ->where('shipper_id', '=', $branch->id)
                ->where('status', 'Completed')
                ->where('project_id', ['334','333','332', '336'])
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $inbounds = AdvanceNotice::where('type', 'inbound')
                ->where('status', 'Completed')
                ->where('project_id', ['334','333','332','336'])
                ->orderBy('created_at', 'desc')
                ->get();
            
            $outbounds = AdvanceNotice::where('type', 'outbound')
                ->where('status', 'Completed')
                ->where('project_id', ['334','333','332','336'])
                ->orderBy('created_at', 'desc')
                ->get();
        }
        foreach ($inbounds as $key => $inbound) {
            $inbound->row_color = NULL;
            // Kalau sudah di buat GR nya, di take out dr list
            if($inbound->transports->count()>0) {
                $inbounds->forget($key);
            }
            // Cek selisih waktu dr mulai dibuat
            $created_time = Carbon::createFromFormat('Y-m-d H:i:s', $inbound->updated_at);
            // Kalau lebih dr 3 jam
            if($created_time->diffInHours()>=3) {
                $inbound->row_color = '#d33724'; // Merah
            } elseif ( ($created_time->diffInHours()>=1) && ($created_time->diffInHours()<3) ) {
                // Antara 1-3 jam
                $inbound->row_color = '#00a65a'; // Kuning
            }
        }
        foreach ($outbounds as $key => $outbound) {
            $outbound->row_color = NULL;
            // Kalau sudah di buat DP nya, di take out dr list
            if($outbound->transports->count()>0) {
                $outbounds->forget($key);
            }
            // Cek selisih waktu dr mulai dibuat
            $created_time = Carbon::parse($outbound->updated_at);
            // Kalau lebih dr 3 jam
            if($created_time->diffInHours()>=3) {
                $outbound->row_color = '#d33724'; // Merah
            } elseif ( ($created_time->diffInHours()>=1) && ($created_time->diffInHours()<3) ) {
                // Antara 1-3 jam
                $outbound->row_color = '#00a65a'; // Kuning
            }
        }
        return view('work_order', compact('inbounds', 'outbounds', 'hour', 'hour3', 'currentTime'));
    }

    public function warehouseActivity(Request $request){
        $selectedRegion = $request->input('region');
        $regionConditions = [];
        if (!empty($selectedRegion)) {
            $regionConditions = ['region_id' => $selectedRegion];
        }

        $regions = Region::where('id','31')->get(['id', 'name']);
        /*$warehouseLocations = Warehouse::whereNotNull('latitude')
            ->where('is_active', 1)
            ->whereNotNull('longitude')
            ->where($regionConditions)
            ->join('cities as c', 'c.id', '=', 'warehouses.city_id')
            ->join('provinces as p', 'p.id', '=', 'warehouses.region_id')
            ->get([
                'warehouses.id',
                'warehouses.code',
                'c.name as area',
                'p.name as region',
                'warehouses.total_weight as total_volume',
                'warehouses.name',
                'warehouses.latitude',
                'warehouses.length',
                'warehouses.width',
                'warehouses.longitude'
            ]);

        $count = 0;
        $count2 = 0;
        $wh1 = array();
        // dd($warehouseLocations);
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
                    $wh->utility_space = round(($wh->total_rented_space/$wh->total_space)*100,2);
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

        // dd($wh1);
        // dd($count2);


        // dd($warehouseLocations->first());
		*/
        return view('warehouse_activity', compact('selectedRegion', 'regions'));
    }
}
