<?php

namespace App\Http\Controllers;

use App\CompanyType;
use Illuminate\Http\Request;

class CompanyTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collections = CompanyType::get();
        return view('company_types.index',compact('collections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $action = route('company_types.store');
        $method = 'POST';
        $companyType = new CompanyType;
        return view('company_types.create',compact('action','method','companyType'));
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

        $model = new CompanyType;
        $model->name = $request->get('name');
        $model->save();
        
        return redirect()->route('company_types.index')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CompanyType  $companyType
     * @return \Illuminate\Http\Response
     */
    public function show(CompanyType $companyType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CompanyType  $companyType
     * @return \Illuminate\Http\Response
     */
    public function edit(CompanyType $companyType)
    {
        $action = route('company_types.update', $companyType->id);
        $method = "PUT";
        return view('company_types.edit',compact('action','method','companyType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CompanyType  $companyType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CompanyType $companyType)
    {
        //Validate
        $request->validate([
            'name' => 'required'
        ]);

        $model = $companyType;
        $model->name = $request->get('name');
        $model->save();
        return redirect()->route('company_types.index')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CompanyType  $companyType
     * @return \Illuminate\Http\Response
     */
    public function destroy(CompanyType $companyType)
    {
        if($companyType->companies->count()>0){
            return redirect('company_types')->with('message','Data tidak bisa dihapus');
        } else {
            $companyType->delete();
            return redirect('company_types')->with('success','Data berhasil dihapus');
        }
    }
}
