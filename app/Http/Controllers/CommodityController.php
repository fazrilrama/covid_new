<?php

namespace App\Http\Controllers;

use App\Commodity;
use Illuminate\Http\Request;

class CommodityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collections = Commodity::get();
        return view('commodities.index',compact('collections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $action = route('commodities.store');
        $method = 'POST';
        $commodity = new Commodity;
        return view('commodities.create',compact('action','method','commodity'));
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
            'code' => 'required|alpha_dash|unique:commodities,code',
            'name' => 'required'
        ]);

        $model = new Commodity;
        $model->name = $request->get('name');
        $model->code = $request->get('code');
        $model->save();
        
        return redirect()->route('commodities.index')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Commodity  $commodity
     * @return \Illuminate\Http\Response
     */
    public function show(Commodity $commodity)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Commodity  $commodity
     * @return \Illuminate\Http\Response
     */
    public function edit(Commodity $commodity)
    {
        $action = route('commodities.update', $commodity->id);
        $method = "PUT";
        return view('commodities.edit',compact('action','method','commodity'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Commodity  $commodity
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Commodity $commodity)
    {
        //Validate
        $request->validate([
            'code' => 'required|alpha_dash|unique:commodities,code,'.$commodity->id,
            'name' => 'required'
        ]);

        $model = $commodity;
        $model->name = $request->get('name');
        $model->code = $request->get('code');
        $model->save();
        return redirect()->route('commodities.index')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Commodity  $commodity
     * @return \Illuminate\Http\Response
     */
    public function destroy(Commodity $commodity)
    {
        if($commodity->storages->count()>0){
            return redirect('commodities')->with('message','Data tidak bisa dihapus');
        } elseif ($commodity->items->count()>0) {
            return redirect('commodities')->with('message','Data tidak bisa dihapus');
        } else {
            $commodity->delete();
            return redirect('commodities')->with('success','Data berhasil dihapus');
        }
    }
}
