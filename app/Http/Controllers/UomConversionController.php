<?php

namespace App\Http\Controllers;

use App\UomConversion;
use App\Uom;
use Illuminate\Http\Request;

class UomConversionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collections = UomConversion::all();
        return view('uom_conversions.index',compact('collections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $action = route('uom_conversions.store');
        $method = 'POST';
        $uoms = Uom::pluck('name', 'id');
        $uomConversion = new UomConversion;
        return view('uom_conversions.create',compact('action','method','uomConversion','uoms'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validate
        $request->validate([
            'multiplier' => 'required|integer',
            'to_uom_id' => 'unique:uom_conversions,to_uom_id,NULL,id,from_uom_id,' . $request->from_uom_id
        ]);

        $model = new UomConversion;
        $model->from_uom_id = $request->get('from_uom_id');
        $model->to_uom_id = $request->get('to_uom_id');
        $model->multiplier = $request->get('multiplier');
        $model->save();
        
        return redirect()->route('uom_conversions.index')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\UomConversion  $uomConversion
     * @return \Illuminate\Http\Response
     */
    public function show(UomConversion $uomConversion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\UomConversion  $uomConversion
     * @return \Illuminate\Http\Response
     */
    public function edit(UomConversion $uomConversion)
    {
        $action = route('uom_conversions.update', $uomConversion->id);
        $method = 'PUT';
        $uoms = Uom::pluck('name', 'id');
        return view('uom_conversions.edit',compact('action','method','uomConversion','uoms'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UomConversion  $uomConversion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UomConversion $uomConversion)
    {
        //Validate
        $request->validate([
            'multiplier' => 'required|integer',
            'to_uom_id' => 'unique:uom_conversions,to_uom_id,'.$uomConversion->id.',id,from_uom_id,' . $request->from_uom_id
        ]);

        $model = $uomConversion;
        $model->from_uom_id = $request->get('from_uom_id');
        $model->to_uom_id = $request->get('to_uom_id');
        $model->multiplier = $request->get('multiplier');
        $model->save();
        
        return redirect()->route('uom_conversions.index')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UomConversion  $uomConversion
     * @return \Illuminate\Http\Response
     */
    public function destroy(UomConversion $uomConversion)
    {
        $uomConversion->delete();
        return redirect('uom_conversions')->with('success','Data berhasil dihapus');
    }
}
