<?php

namespace App\Http\Controllers;

use App\Uom;
use Illuminate\Http\Request;

class UomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collections = Uom::get();
        return view('uoms.index',compact('collections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $action = route('uoms.store');
        $method = 'POST';
        $uom = new Uom;
        return view('uoms.create',compact('action','method','uom'));
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
            'name' => 'required|unique:uoms,name'
        ]);

        $model = new Uom;
        $model->name = $request->get('name');
        $model->save();
        
        return redirect()->route('uoms.index')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Uom  $uom
     * @return \Illuminate\Http\Response
     */
    public function show(Uom $uom)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Uom  $uom
     * @return \Illuminate\Http\Response
     */
    public function edit(Uom $uom)
    {
        $action = route('uoms.update', $uom->id);
        $method = "PUT";
        return view('uoms.edit',compact('action','method','uom'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Uom  $uom
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Uom $uom)
    {
        //Validate
        $request->validate([
            'name' => 'required|unique:uoms,name,'.$uom->id
        ]);

        $model = $uom;
        $model->name = $request->get('name');
        $model->save();
        return redirect()->route('uoms.index')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Uom  $uom
     * @return \Illuminate\Http\Response
     */
    public function destroy(Uom $uom)
    {
        if($uom->items->count()>0) {
            return redirect('uoms')->with('message','Data tidak bisa dihapus');
        } else {
            $uom->delete();
            return redirect('uoms')->with('success','Data berhasil dihapus');
        }
    }
}
