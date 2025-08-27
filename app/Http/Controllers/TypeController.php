<?php

namespace App\Http\Controllers;

use App\Type;
use Illuminate\Http\Request;
use App\Commodity;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collections = Type::get();
        return view('types.index',compact('collections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $action = route('types.store');
        $method = 'POST';
        $type = new Type;
        $commodities = Commodity::get(['name', 'code', 'id']);
        return view('types.create',compact('action','method','type', 'commodities'));
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
            'name' => 'required|unique:types,name',
            'commodity_id' => 'required'
        ]);

        $model = new Type;
        $model->commodity_id = $request->get('commodity_id');
        $model->name = $request->get('name');
        $model->save();
        
        return redirect()->route('types.index')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function show(Type $type)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function edit(Type $type)
    {
        $action = route('types.update', $type->id);
        $method = "PUT";
        $commodities = Commodity::get(['name', 'code', 'id']);
        return view('types.edit',compact('action','method','type', 'commodities'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Type $type)
    {
        //Validate
        $request->validate([
            'name' => 'required|unique:types,name,'.$type->id,
            'commodity_id' => 'required'
        ]);

        $model = $type;
        $model->commodity_id = $request->get('commodity_id');
        $model->name = $request->get('name');
        $model->save();
        return redirect()->route('types.index')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function destroy(Type $type)
    {
        $type->delete();
        return redirect('types')->with('success','Data berhasil dihapus');
    }
}
