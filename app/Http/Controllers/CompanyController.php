<?php

namespace App\Http\Controllers;

use App\Company;
use App\City;
use App\CompanyType;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collections = Company::get();
        return view('companies.index',compact('collections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $action = route('companies.store');
        $method = 'POST';
        $cities = City::pluck('name', 'id');
        $company_types = CompanyType::pluck('name', 'id');
        $company = new Company;
        return view('companies.create',compact('action','method','company','cities','company_types'));
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
            'code' => 'required|alpha_dash|unique:companies,code',
            //'name' => 'required|regex:/^[1-9a-zA-Z.,\- ]+$/u',
            'name' => 'required',
            'address' => 'required',
            'postal_code' => 'required|min:5|integer',
            'phone_number' => 'required|numeric|min:6',
            'fax_number' => 'nullable|numeric|min:6'
        ]);

        $model = new Company;
        $model->code = $request->get('code');
        $model->name = $request->get('name');
        $model->address = $request->get('address');
        $model->postal_code = $request->get('postal_code');
        $model->phone_number = $request->get('phone_number');
        $model->fax_number = $request->get('fax_number');
        $model->city_id = $request->get('city_id');
        $model->company_type_id = $request->get('company_type_id');
        $model->save();
        
        return redirect()->route('companies.index')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        $action = route('companies.update', $company->id);
        $method = "PUT";
        $cities = City::pluck('name', 'id');
        $company_types = CompanyType::pluck('name', 'id');
        return view('companies.edit',compact('action','method','company','cities','company_types','company'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        //Validate
        $request->validate([
            'code' => 'required|alpha_dash|unique:companies,code,'.$company->id,
            'name' => 'required',
            'address' => 'required',
            'postal_code' => 'required|min:5|integer',
            'phone_number' => 'required|numeric|min:6',
            'fax_number' => 'nullable|numeric|min:6'
        ]);

        $model = $company;
        $model->code = $request->get('code');
        $model->name = $request->get('name');
        $model->address = $request->get('address');
        $model->postal_code = $request->get('postal_code');
        $model->phone_number = $request->get('phone_number');
        $model->fax_number = $request->get('fax_number');
        $model->city_id = $request->get('city_id');
        $model->company_type_id = $request->get('company_type_id');
        $model->save();
        
        return redirect()->route('companies.index')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        // if($company->users->count()>0) {
        //     return redirect('companies')->with('message','Data tidak bisa dihapus');
        // } else {
            $company->delete();
            return redirect('companies')->with('success','Data berhasil dihapus');
        // }
    }

    public function users(Company $company)
    {
        $collections = $company->users()->orderBy('id', 'desc')->get();
        return view('companies.users',compact('collections','company'));
    }
}
