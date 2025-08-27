<?php

namespace App\Http\Controllers;

use App\City;
use App\Region;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collections = City::get();
        return view('cities.index',compact('collections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $action = route('cities.store');
        $method = 'POST';
        $regions = Region::get();
        $city = new City;
        return view('cities.create',compact('action','method','city','regions'));
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
            //'id' => 'required|max:9999|numeric|unique:cities,id',
            //'code' => 'required|unique:cities,code',
            'name' => 'required|unique:cities,name',
            'region_id' => 'required'
        ]);

        // $region = Region::orderBy('id','desc')->first();
        // $last_id = $region->id;

        $model = new City;
        $model->id = $request->get('id');
        $model->code = $request->get('code');
        $model->name = $request->get('name');
        $model->province_id = $request->get('region_id');
        // $model->id = $last_id+1;
        $model->save();
        
        return redirect()->route('cities.index')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\City  $city
     * @return \Illuminate\Http\Response
     */
    public function show(City $city)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\City  $city
     * @return \Illuminate\Http\Response
     */
    public function edit(City $city)
    {
        $action = route('cities.update', $city->id);
        $method = "PUT";
        $regions = Region::get();
        return view('cities.edit',compact('action','method','city','regions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\City  $city
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, City $city)
    {
        //Validate
        $request->validate([
            //'id' => 'required|max:9999|numeric|unique:cities,id,'.$city->id,
            //'code' => 'required|unique:cities,code,'.$city->id,
            'name' => 'required',
            'region_id' => 'required'
        ]);

        $model = $city;
        $model->id = $request->get('id');
        $model->code = $request->get('code');
        $model->name = $request->get('name');
        $model->province_id = $request->get('region_id');
        $model->save();
        return redirect()->route('cities.index')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\City  $city
     * @return \Illuminate\Http\Response
     */
    public function destroy(City $city)
    {
        if($city->warehouses->count()>0) {
            return redirect('cities')->with('message','Data tidak bisa dihapus');
        } elseif ($city->companies->count()>0) {
            return redirect('cities')->with('message','Data tidak bisa dihapus');
        } else {
            $city->delete();
            return redirect('cities')->with('success','Data berhasil dihapus');
        }
    }

    public function getList()
    {
        $region = request('region_id');
        $cities = City::where('province_id', $region)->get();
        return view('cities.select_city', compact('cities'));
    }
}
