<?php

namespace App\Http\Controllers;

use App\Imports\sku_import;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
	
class ImportController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }

    public function import(Request $request) 
    {

        
    	if ($request->file('import') != null) {

            // for($i=34;$i<=216;$i++){
            //     $itemProject = New ItemProjects;
            //     $itemProject->item_id = $i;
            //     $itemProject->save();
            // }

            // $get = ItemProjects::whereBetween('item_id',[34,216])->get();

            // foreach($get as $g){
            //     $item = ItemProjects::find($g->id);
            //     $item->project_id = 215;
            //     $item->save();
            // }

            Excel::import(new sku_import, $request->file('import'));
        
        	return redirect('to_import')->with('success', 'All good!');
        }
        
    }

    public function to_import(Request $request) 
    {
        $action = route('import');
        $method = "POST";
        return view('import.import',compact('action','method'));
    }

}
