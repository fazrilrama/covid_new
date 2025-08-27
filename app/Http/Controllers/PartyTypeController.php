<?php

namespace App\Http\Controllers;

use App\PartyType;
use Illuminate\Http\Request;

class PartyTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collections = PartyType::get();
        return view('party_types.index',compact('collections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $action = route('party_types.store');
        $method = 'POST';
        $partyType = new PartyType;
        return view('party_types.create',compact('action','method','partyType'));
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
            'name' => 'required'
        ]);

        $model = new PartyType;
        $model->name = $request->get('name');
        $model->save();
        
        return redirect()->route('party_types.index')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PartyType  $partyType
     * @return \Illuminate\Http\Response
     */
    public function show(PartyType $partyType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PartyType  $partyType
     * @return \Illuminate\Http\Response
     */
    public function edit(PartyType $partyType)
    {
        $action = route('party_types.update', $partyType->id);
        $method = "PUT";
        return view('party_types.edit',compact('action','method','partyType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PartyType  $partyType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PartyType $partyType)
    {
        //Validate
        $request->validate([
            'name' => 'required'
        ]);

        $model = $partyType;
        $model->name = $request->get('name');
        $model->save();
        
        return redirect()->route('party_types.index')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PartyType  $partyType
     * @return \Illuminate\Http\Response
     */
    public function destroy(PartyType $partyType)
    {
        if($partyType->parties->count()>0){
            return redirect('party_types')->with('message','Data tidak bisa dihapus');
        } else {
            $partyType->delete();
            return redirect('party_types')->with('success','Data berhasil dihapus');
        }        
    }
}
