<?php

namespace App\Http\Controllers;

use App\Project;
use App\StorageProjects;
use App\Storage;
use App\Warehouse;
use App\Party;
use App\Company;
use Auth;
use DB;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        if(Auth::user()->hasRole('Superadmin')) {
            $collections = Project::get();
        } else {
            $collections = session()->get('projects');
            //$collections = Auth::user()->company->projects;
        }
        return view('projects.index',compact('collections'));
    }

    public function create()
    {
        $action = route('projects.store');
        $method = 'POST';
        $project = new Project;
        $companies = Company::pluck('name','id');
        $isSuperadmin = Auth::user()->hasRole('Superadmin');
        $namingSeries = Company::first()->code;
        //$storages = Storage::get(['id', 'code']);

        if(!empty($namingSeries)) {
            $length = strlen($namingSeries) + 1;
            $projectDump = Project::select(DB::raw('SUBSTRING(project_id, '.$length.') as name'))
                ->where(DB::raw('SUBSTRING(project_id, 1, '. ($length - 1) .')'), $namingSeries)
                ->orderBy('id', 'DESC')
                ->first();
        }

        $namingSeries .= empty($projectDump->name) ? 10001 : (intval($projectDump->name) + 1);

        return view('projects.create',compact('action','method','project','companies', 'namingSeries', 'isSuperadmin'));
    }

    public function store(Request $request)
    {
        //Validate
        $request->validate([
            'project_id' => 'required|unique:projects,project_id',
            'name' => 'required',
            'description' => 'required',
        ]);

        $model = new Project;
        $model->name = $request->get('name');
        $model->project_id = $request->get('project_id');
        $model->company_id = $request->get('company_id');
        $model->description = $request->get('description');
        $model->save();

        return redirect()->route('projects.index')->with('success', 'Data berhasil disimpan');
    }

    public function show(Party $party)
    {
        //
    }

    public function edit(Project $project)
    {
        $action = route('projects.update', $project->id);
        $method = "PUT";
        $companies = Company::pluck('name', 'id');
        
        //$storages = Storage::get(['id', 'code']);
        $isSuperadmin = Auth::user()->hasRole('Superadmin');

        $project_storage = StorageProjects::where('project_id' ,$project->id)->get();

        return view('projects.edit',compact('action','method','project','companies', 'isSuperadmin','project_storage'));
    }

    public function update(Request $request, Project $project)
    {
        //Validate
        $request->validate([
            //'project_id' => 'required|unique:projects,project_id,' . $request->project_id,
            'name' => 'required',
            'description' => 'required',
        ]);

        $model = $project;
        $model->name = $request->get('name');
        $model->project_owner = $request->get('project_owner');
        $model->description = $request->get('description');
        $model->company_id = $request->get('company_id');
        $model->save();

        return redirect()->route('projects.index')->with('success', 'Data berhasil disimpan');
    }

    public function destroy(Project $project)
    {
        $hasRelations = FALSE;
        if($project->users->count()>0) {
            $hasRelations = TRUE;
        }
        if($project->advancenotices->count()>0) {
            $hasRelations = TRUE;
        }
        if($project->stockdeliveries->count()>0) {
            $hasRelations = TRUE;
        }
        if($project->stocktransports->count()>0) {
            $hasRelations = TRUE;
        }
        if($project->item->count()>0) {
            $hasRelations = TRUE;
        }
        if($project->stocks->count()>0) {
            $hasRelations = TRUE;
        }
        if($hasRelations) {
            return redirect('projects')->with('message','Data sudah digunakan tidak bisa dihapus');
        } else {
            $project->delete();
            return redirect('projects')->with('success','Data berhasil dihapus');
        }
    }

    public function users(Project $project)
    {
        $collections = $project->users()->orderBy('id', 'desc')->get();
        return view('projects.users',compact('collections','project'));
    }

    public function update_users(Request $request, User $user)
    {
        //Validate
        $request->validate([
            'users' => 'required|present|filled',
        ]);

        $model = $user;
        $model->users()->sync($request->get('users'));

        // Return edit user model
        return redirect()->route('projects.edit',$model->id)->with('success', 'Data berhasil disimpan');
    }

    public function getWarehouse($party_id) {
        $warehouses = Warehouse::where('branch_id',$party_id)->get();

        return $warehouses;
    }

    public function getStorage($warehouse_id) {
        $storages = Storage::where('warehouse_id',$warehouse_id)->get();

        return $storages;
    }

    public function to_add_project_storage(Project $project){
        $branches = Party::get(['id', 'code','name']);
        $method = "POST";
        $action = route('add_project_storage');

        return view('projects.add_storage',compact('branches','method','action','project'));
    }

    public function add_project_storage(Request $request){
        $storage = Storage::find($request->input('storage_id'));
        $project = Project::find($request->input('project_id'));

        $project->storage()->syncWithoutDetaching($storage->id);

        return redirect()->route('projects.edit', $project)->with('success', 'Data berhasil disimpan');
    }

    public function delete_project_storage(Request $request){
        $StorageProjects = StorageProjects::find($request->input('storage_project_id'));
        $project = Project::find($request->input('project_id'));
        
        $StorageProjects->delete();

        return redirect()->route('projects.edit', $project)->with('success', 'Data berhasil dihapus');
    }
}
