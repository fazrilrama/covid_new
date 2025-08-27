<?php

namespace App\Http\Controllers;

use App\Packing;
use Illuminate\Http\Request;
use App\Commodity;
use Illuminate\Validation\Rule;

class PackingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collections = Packing::get();
        return view('packings.index',compact('collections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $action = route('packings.store');
        $method = 'POST';
        $packing = new Packing;
        $commodities = Commodity::get(['name', 'code', 'id']);
        return view('packings.create',compact('action','method','packing', 'commodities'));
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
        $name = $request->get('name');
        $commodity_id = $request->get('commodity_id');

        $request->validate([
            'name' => [
                'required',
                Rule::unique('packings')->where(function ($query) use($name,$commodity_id) {
                    return $query->where('name', $name)
                    ->where('commodity_id', $commodity_id);
                }),
            ],
            'commodity_id' => 'required'
        ]);

        $model = new Packing;
        $model->commodity_id = $request->get('commodity_id');
        $model->name = $request->get('name');
        $model->save();
        
        return redirect()->route('packings.index')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Packing  $packing
     * @return \Illuminate\Http\Response
     */
    public function show(Packing $packing)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Packing  $packing
     * @return \Illuminate\Http\Response
     */
    public function edit(Packing $packing)
    {
        $action = route('packings.update', $packing->id);
        $method = "PUT";
        $commodities = Commodity::get(['name', 'code', 'id']);
        return view('packings.edit',compact('action','method','packing', 'commodities'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Packing  $packing
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Packing $packing)
    {
        //Validate
        $name = $request->get('name');
        $commodity_id = $request->get('commodity_id');

        $request->validate([
            'name' => [
                'required',
                Rule::unique('packings')->where(function ($query) use($name,$commodity_id) {
                    return $query->where('name', $name)
                    ->where('commodity_id', $commodity_id);
                }),
            ],
            'commodity_id' => 'required'
        ]);

        $model = $packing;
        $model->commodity_id = $request->get('commodity_id');
        $model->name = $request->get('name');
        $model->save();
        return redirect()->route('packings.index')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Packing  $packing
     * @return \Illuminate\Http\Response
     */
    public function destroy(Packing $packing)
    {
        $packing->delete();
        return redirect('packings')->with('success','Data berhasil dihapus');
    }
}
