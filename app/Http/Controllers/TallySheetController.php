<?php

namespace App\Http\Controllers;

use App\AdvanceNotice;
use App\City;
use App\Party;
use App\StockTransport;
use App\TransportType;
use Auth;
use App\StockEntry;
use Illuminate\Http\Request;


class TallySheetController extends Controller
{
    public function index()
    {
        $collections = StockTransport::get();
        return view('tally_sheet.index',compact('collections'));
    }

    public function show(StockTransport $stockTransport)
    {
        $type = $stockTransport->type;
        $action = route('stock_transports.update', $stockTransport->id);
        $method = 'PUT';
        $cities = City::pluck('name','id');
        $transport_types = TransportType::pluck('name','id');
        $advance_notices = AdvanceNotice::where('type',$type)->pluck('code','id');
        $shippers = Party::whereHas('party_types', function ($query) {
            $query->where('name','shipper');
        })->get();
        $consignees = Party::whereHas('party_types', function ($query) {
            $query->where('name','consignee');
        })->get();
        $employees = Party::whereHas('party_types', function ($query) {
            $query->where('name','employee');
        })->get();
        $companies = Auth::user()->companies->pluck('name','id');
        return view('tally_sheet.view',compact('action','method','stockTransport','transport_types','advance_notices','cities','shippers','consignees','employees','type','companies'));
    }
}
