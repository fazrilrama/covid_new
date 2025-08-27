<?php

namespace App\Http\Controllers;

use App\Role;
use App\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collections = Role::where('id','!=',1)->orderBy('id', 'desc')->get();
        return view('roles.index',compact('collections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $action = route('roles.store');
        $method = 'POST';
        $role = new Role;
        $permissions = Permission::all();
        return view('roles.create',compact('action','method','role','permissions'));
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
            'name' => 'required|unique:roles,name',
            'permissions' => 'present|filled'
        ]);

        $model = new Role;
        $model->name = $request->get('name');
        $model->save();

        $model->permissions()->sync($request->get('permissions'));
        
        return redirect()->route('roles.index')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $action = route('roles.update', $role->id);
        $method = "PUT";
        $permissions = Permission::all();
        return view('roles.edit',compact('action','method','role','permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        //Validate
        $request->validate([
            'name' => 'required|unique:roles,name,'.$role->id
        ]);

        $model = $role;
        $model->name = $request->get('name');
        $model->save();

        $model->permissions()->sync($request->get('permissions'));
        
        return redirect()->route('roles.index')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        if($role->users()->count() > 0) {
            return redirect('roles')->with('message','Data tidak bisa dihapus');
        } else {
            $role->delete();
            return redirect('roles')->with('success','Data berhasil dihapus');
        }
    }
}
