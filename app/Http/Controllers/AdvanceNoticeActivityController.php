<?php

namespace App\Http\Controllers;

use App\AdvanceNoticeActivity;
use Illuminate\Http\Request;

class AdvanceNoticeActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collections = AdvanceNoticeActivity::get();
        return view('advance_notice_activities.index',compact('collections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $action = route('advance_notice_activities.store');
        $method = 'POST';
        $advanceNoticeActivity = new AdvanceNoticeActivity;
        return view('advance_notice_activities.create',compact('action','method','advanceNoticeActivity'));
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

        $model = new AdvanceNoticeActivity;
        $model->name = $request->get('name');
        $model->save();
        
        return redirect()->route('advance_notice_activities.index')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AdvanceNoticeActivity  $advanceNoticeActivity
     * @return \Illuminate\Http\Response
     */
    public function show(AdvanceNoticeActivity $advanceNoticeActivity)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AdvanceNoticeActivity  $advanceNoticeActivity
     * @return \Illuminate\Http\Response
     */
    public function edit(AdvanceNoticeActivity $advanceNoticeActivity)
    {
        $action = route('advance_notice_activities.update', $advanceNoticeActivity->id);
        $method = "PUT";
        return view('advance_notice_activities.edit',compact('action','method','advanceNoticeActivity'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AdvanceNoticeActivity  $advanceNoticeActivity
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AdvanceNoticeActivity $advanceNoticeActivity)
    {
        //Validate
        $request->validate([
            'name' => 'required'
        ]);

        $model = $advanceNoticeActivity;
        $model->name = $request->get('name');
        $model->save();
        return redirect()->route('advance_notice_activities.index')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AdvanceNoticeActivity  $advanceNoticeActivity
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdvanceNoticeActivity $advanceNoticeActivity)
    {
        if($advanceNoticeActivity->advance_notices->count()>0){
            return redirect('advance_notice_activities')->with('message','Data tidak bisa dihapus');
        } else {
            $advanceNoticeActivity->delete();
            return redirect('advance_notice_activities')->with('success','Data berhasil dihapus');
        }
    }
}
