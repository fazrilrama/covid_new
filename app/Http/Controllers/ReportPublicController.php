<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\StockEntry;
use App\StockEntryDetail;
use App\StockDelivery;
use DB;
use App\Contract;
use Carbon\CarbonPeriod;
use App\Providers\DataTablesBuilderService;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportExport;
use App\ItemProjects;
use App\User;
use Carbon\Carbon;
use App\LastLogger;
use DateTime;
use DateTimeZone;
use DateInterval;
use DatePeriod;

class ReportPublicController extends Controller
{
    public function reportMutationPublic(Request $request)
    {
        $data['date_from'] = $request->input('date_from');
        $data['date_to'] = $request->input('date_to');

        $submit = $request->input('submit');

        $search = !empty($data['date_from']) && !empty($data['date_to']);

        $additionalMessage = '';
        $additionalError = true;


        if(empty($data['date_from']) && empty($data['date_to'])){
            $additionalMessage = 'Harap pilih tanggal mulai dan tanggal akhir';
        }else if  (empty($data['date_from'])){
            $additionalMessage = 'Harap pilih tanggal mulai';
        }else if (empty($data['date_to'])){
            $additionalMessage = 'Harap pilih tanggal akhir';
        }

        // dd('sni');
        // $item = Item::find($data['item']);
        $show = 1;
        $collections = [];
        if($search) {
            $stock = StockEntry::join('stock_entry_details', 'stock_entry_details.stock_entry_id', 'stock_entries.id')->select(['parties.name as branch','warehouses.name', 'warehouses.code','warehouses.ownership',DB::raw('sum(case when type = "inbound" then qty else 0 end) as qty_inbound'),
            DB::raw('sum(case when type = "outbound" then qty else 0 end) as qty_outbound'), DB::raw('(sum(case when type = "inbound" then qty else 0 end) + sum(case when type = "outbound" then qty else 0 end)) as turnover')])
            // ->with('warehouse', 'warehouse.branch')
            ->join('warehouses', 'warehouses.id', 'stock_entry_details.warehouse_id')
            ->join('parties', 'parties.id', 'warehouses.branch_id')
            ->whereBetween('stock_entry_details.updated_at', [$data['date_from'], $data['date_to']])
            ->where('is_active', '1')
            ->groupBy('stock_entry_details.warehouse_id')
            ->get();
        }

        if($submit == 1){
            return Excel::download(new ReportExport($stock,10), 'turnover.xlsx');
        }
        else{
            return view('report.stockMutation_public', compact('data','show','stock', 'search'));
        }
    }

    public function reportFromQr(StockDelivery $stockDelivery)
    {
        return view('stock_deliveries.qr')->with([
            'stockDelivery' => $stockDelivery
        ]);
    }

    public function daily_logins($day = null, Request $request)
    {
        if($request->bearerToken() != null) {
            $user = User::where('api_token', $request->bearerToken())->first();
            if(!$user) {
                return response()->json('Not authenticate!');
            }
        }  else {
            return response()->json('Not authenticate!');
        }

        if($day == null) {
            $day = 14;
        }
		$data = LastLogger::selectRaw('count(created_at) as count, DATE_FORMAT(created_at, "%Y-%m-%d") as date')
            ->whereBetween(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), [Carbon::now()->subDays($day)->format('Y-m-d'),Carbon::now()->format('Y-m-d')])
            ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'))
            ->get();
        // dd($data);
        // dd($data);
		$startDate = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
		$startDate->modify("-$day day");

		$endDate = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
		$endDate->modify("+1 day");

		$interval = new DateInterval("P1D");

		$dateRange = new DatePeriod($startDate, $interval, $endDate);

		$dateCount = [];
        // dd($dateRange);
		foreach ($dateRange as $date) {
			$dateCount[$date->format("Y-m-d")] = 0;
		}
        // return response()->json($data);
		foreach ($data as $datum) {
			$dateCount[$datum->date] = (int) $datum->count;
		}

        // echo json_encode($dateCount, TRUE);
        return response()->json($dateCount);
        // ($user->validTo->subDays(30)->gt(Carbon::now())

    }

    public function wpItems(Request $request)
    {
        if($request->bearerToken() != null) {
            $user = User::where('api_token', $request->bearerToken())->first();
            if(!$user) {
                return response()->json([
                    'status' => false,
                    'message'=>'Not authenticate!'
                ]);
            }
        }  else {
            return response()->json([
                'status' => false,
                'message'=>'Not authenticate!'
            ]);
        }

        $validator = \Validator::make($request->all(), [
            'date_from' => 'required|date',
            'date_to' => 'required|date',
        ]);
    
        $errors = $validator->errors();
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->messages()
            ]);
        }else{
            
            $items = ItemProjects::select(['items.name', 'items.created_at'])->join('items', 'items.id', 'item_projects.item_id')->where('item_projects.project_id', 337);
            if($request->date_from != null & $request->date_to != null) {
                $items->where('items.created_at', '>=', $request->date_from . ' 00:00:00')
                    ->where('items.created_at', '<=', $request->date_to . ' 23:59:59');
            }
            $items = $items->count();

            // dd($items);

            $items_old = ItemProjects::select(['items.name', 'items.created_at'])->join('items', 'items.id', 'item_projects.item_id')->where('item_projects.project_id', 337);
            if($request->date_from != null & $request->date_to != null) {
                $items_old->where('items.created_at', '<', $request->date_from . ' 00:00:00');
            }
            $items_old = $items_old->count();

            // dd($items_old);
                
            $data['old'] = $items_old;
            $data['new'] = $items;
            $data['total_item'] = $items_old + $items;

            return response()->json([
                'status' => true,
                'message'=>'Success',
                'data' => $data
            ]);   
        }
    }
}
