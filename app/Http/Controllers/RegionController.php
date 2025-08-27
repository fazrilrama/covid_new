<?php

namespace App\Http\Controllers;

use App\Region;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {           
        $collections = Region::get();
        return view('regions.index',compact('collections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $action = route('regions.store');
        $method = 'POST';
        $region = new Region;
        return view('regions.create',compact('action','method','region'));
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
            'id' => 'required|max:999|numeric|unique:provinces,id',
            'code' => 'required|unique:provinces,code',
            'name' => 'required|unique:provinces,name'
        ]);

        $model = new Region;
        $model->name = $request->get('name');
        $model->code = $request->get('code');
        $model->id = $request->get('id');
        $model->save();
        
        return redirect()->route('regions.index')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Region  $region
     * @return \Illuminate\Http\Response
     */
    public function show(Region $region)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Region  $region
     * @return \Illuminate\Http\Response
     */
    public function edit(Region $region)
    {
        $action = route('regions.update', $region->id);
        $method = "PUT";
        return view('regions.edit',compact('action','method','region'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Region  $region
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Region $region)
    {
        //Validate
        $request->validate([
            'id' => 'required|numeric|alpha_dash|unique:provinces,id,'.$region->id,
            'code' => 'required|unique:provinces,code,'.$region->id,
            'name' => 'required'
        ]);

        $model = $region;
        $model->name = $request->get('name');
        $model->code = $request->get('code');
        $model->id = $request->get('id');
        $model->save();
        return redirect()->route('regions.index')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Region  $region
     * @return \Illuminate\Http\Response
     */
    public function destroy(Region $region)
    {
        if($region->warehouses->count()>0){
            return redirect('regions')->with('message','Data sudah mempunyai gudang tidak bisa dihapus');
        } else {
            $region->delete();
            return redirect('regions')->with('success','Data berhasil dihapus');
        }
    }
}
