<?php

namespace App\Http\Controllers;

use App\Contract;
use App\Project;
use App\Company;
use App\Spk;
use App\Warehouse;
use Illuminate\Http\Request;
use Auth;

class SpkController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            if ( $user->hasRole('Superadmin') ) {
            }else if ( $user->hasRole('Admin') || $user->hasRole('Admin-BGR') || $user->hasRole('Admin-Client') ) {
                if (empty(session()->get('current_project'))) {
                    return redirect('empty-project');
                }
            }else if (empty(session()->get('current_project'))) {
                return redirect('empty-project');
            }

            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->hasRole('Superadmin')) {
            $collections = Spk::orderBy('id', 'desc')->get();
        } else {
            $projectId = session()->get('current_project')->id;
            $contracts = Project::findOrFail($projectId)->contract()->pluck('id');
            $collections = Spk::whereIn('contract_id', $contracts)->orderBy('id', 'desc')->get();
        }
        return view('spks.index',compact('collections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $action = route('spks.store');
        $method = 'POST';

        if(session()->get('current_project')){
            $contracts = Contract::where('is_active','1')
                        ->where('project_id', session()->get('current_project')->id)
                        ->get()->pluck('number_contract','id');
        }
        else{
            $contracts = Contract::where('is_active','1')->get()->pluck('number_contract','id');
        }
        
        $projects = Auth::user()->projects;
        $spk = new Spk;
        return view('spks.create',compact('contracts','projects','action','method','spk'));
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
            'contract_id' => 'required',
            'number_spk' => 'required',
        ]);

        $model = new Spk;
        $model->contract_id = $request->get('contract_id');
        $model->number_spk = $request->get('number_spk');
        $model->save();
        
        return redirect()->route('spks.index')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Spk $spk)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Spk $spk)
    {
        $action = route('spks.update', $spk->id);
        $method = 'PUT';
        $contracts = Contract::where('is_active','1')->get()->pluck('number_contract','id');
        $projects = Auth::user()->projects;
        return view('spks.edit',compact('projects','action','method','spk','contracts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Spk $spk)
    {
        //Validate
        $request->validate([
            'contract_id' => 'required',
            'number_spk' => 'required',
        ]);

        $model = $spk;
        $model->number_spk = $request->get('number_spk');
        $model->contract_id = $request->get('contract_id');
        $model->save();
        return redirect()->route('spks.index')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Spk $spk)
    {
        $spk->delete();
        return redirect('spks')->with('success','Data berhasil dihapus');
    }
}
