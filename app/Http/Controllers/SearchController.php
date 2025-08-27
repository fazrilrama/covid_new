<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function autocomplete()
    {
        $term = Input::get('term');
        $results = array();
        $projects = Auth::User()->projects;
    
        foreach ($projects as $project) {
            $results[] = [ 'id' => $project->id, 'value' => $project->name];
        }
        
        return Response::json($results);
    }
}