<?php

namespace App\Http\Controllers;

use App\TransportType;
use Illuminate\Http\Request;

class TransportTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collections = TransportType::get();
        return view('transport_types.index',compact('collections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $action = route('transport_types.store');
        $method = 'POST';
        $transportType = new transportType;
        return view('transport_types.create',compact('action','method','transportType'));
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
            'name' => 'required|unique:transport_types,name'
        ]);

        $model = new TransportType;
        $model->name = $request->get('name');
        $model->save();
        
        return redirect()->route('transport_types.index')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TransportType  $transportType
     * @return \Illuminate\Http\Response
     */
    public function show(TransportType $transportType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TransportType  $transportType
     * @return \Illuminate\Http\Response
     */
    public function edit(TransportType $transportType)
    {
        $action = route('transport_types.update', $transportType->id);
        $method = "PUT";
        return view('transport_types.edit',compact('action','method','transportType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TransportType  $transportType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TransportType $transportType)
    {
        //Validate
        $request->validate([
            'name' => 'required|unique:transport_types,name,'.$transportType->id
        ]);

        $model = $transportType;
        $model->name = $request->get('name');
        $model->save();
        return redirect()->route('transport_types.index')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TransportType  $transportType
     * @return \Illuminate\Http\Response
     */
    public function destroy(TransportType $transportType)
    {
        if(TransportType::count()==1){
            return redirect('transport_types')->with('message','Data tidak bisa dihapus');
        } else {
            $transportType->delete();
            return redirect('transport_types')->with('success','Data berhasil dihapus');
        }
    }
}
