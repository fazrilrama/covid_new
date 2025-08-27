<?php

namespace App\Http\Controllers;

use App\StockDelivery;
use App\StockDeliveryDetail;
use App\Item;
use App\DataLog;
use App\Uom;
use App\Project;
use App\StockEntry;
use Auth;
use Illuminate\Http\Request;

class StockDeliveryDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $collections = StockDeliveryDetail::orderBy('id', 'desc')->get();
        // return view('stock_delivery_details.index',compact('collections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($stock_delivery_id = NULL)
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\StockDeliveryDetail  $stockDeliveryDetail
     * @return \Illuminate\Http\Response
     */
    public function show(StockDeliveryDetail $stockDeliveryDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StockDeliveryDetail  $stockDeliveryDetail
     * @return \Illuminate\Http\Response
     */
    public function edit(StockDeliveryDetail $stockDeliveryDetail)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StockDeliveryDetail  $stockDeliveryDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StockDeliveryDetail $stockDeliveryDetail)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StockDeliveryDetail  $stockDeliveryDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(StockDeliveryDetail $stockDeliveryDetail)
    {
        
    }
}
