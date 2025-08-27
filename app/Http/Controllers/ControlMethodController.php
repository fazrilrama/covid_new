<?php

namespace App\Http\Controllers;

use App\ControlMethod;
use Illuminate\Http\Request;

class ControlMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collections = ControlMethod::get();
        return view('control_methods.index',compact('collections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $action = route('control_methods.store');
        $method = 'POST';
        $controlMethod = new ControlMethod;
        return view('control_methods.create',compact('action','method','controlMethod'));
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

        $model = new ControlMethod;
        $model->name = $request->get('name');
        $model->description = $request->get('description');
        $model->save();
        
        return redirect()->route('control_methods.index')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ControlMethod  $controlMethod
     * @return \Illuminate\Http\Response
     */
    public function show(ControlMethod $controlMethod)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ControlMethod  $controlMethod
     * @return \Illuminate\Http\Response
     */
    public function edit(ControlMethod $controlMethod)
    {
        $action = route('control_methods.update', $controlMethod->id);
        $method = "PUT";
        return view('control_methods.edit',compact('action','method','controlMethod'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ControlMethod  $controlMethod
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ControlMethod $controlMethod)
    {
        //Validate
        $request->validate([
            'name' => 'required'
        ]);

        $model = $controlMethod;
        $model->name = $request->get('name');
        $model->description = $request->get('description');
        $model->save();
        return redirect()->route('control_methods.index')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ControlMethod  $controlMethod
     * @return \Illuminate\Http\Response
     */
    public function destroy(ControlMethod $controlMethod)
    {
        if($controlMethod->items->count()>0){
            return redirect('control_methods')->with('message','Data tidak bisa dihapus');
        } else {
            $controlMethod->delete();
            return redirect('control_methods')->with('success','Data berhasil dihapus');
        }
    }
}
