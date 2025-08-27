<?php

namespace App\Http\Controllers;

use App\Party;
use App\Storage;
use App\PartyType;
use App\PartiesPartyTypes;
use App\City;
use App\Region;
use Illuminate\Http\Request;
use View;

class PartyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collections = Party::orderBy('id','asc')->get();
        //dd($collections);
        return view('parties.index',compact('collections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $action = route('parties.store');
        $method = 'POST';
        $cities = City::pluck('name', 'id');
        $party_types = PartyType::whereNotIn('id',[99])->orderBy('order_item', 'asc')->get();
        $party = new Party;
        //kantor pusat tidak muncul
        $parties = Party::where('parent_id',1)->where('id','<>',1)->get();
        $regions = Region::pluck('name', 'id');
        return view('parties.create',compact('action','method','party','cities','party_types', 'regions', 'parties'));
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
            'code' => 'required',
            'name' => 'required',
            'address' => 'required',
            'longitude' => 'required',
            'latitude' => 'required',
            'party_types' => 'required',
            'postal_code' => 'required|min:5|integer',
            'phone_number' => 'required|numeric|min:6'
        ]);

        $parent_id = 0;

        if (in_array('branch', $request->get('party_types'))) {
            $parent_id = 1;
        }
        // if (in_array('Subdivre', $request->get('party_types'))) {
        //     $parent_id = $request->get('parent_id');
        // }

        $string_type = $request->get('party_types');
        $int_type = array();

        foreach($string_type as $st){
            $single_int_type = PartyType::where('name',$st)->pluck('id');

            array_push($int_type, $single_int_type[0]);
        }

        $data = explode(',',$request->get('region_id'));

        $model = new Party;
        $model->code = $request->get('code');
        $model->name = strtoupper($request->get('name'));
        $model->address = $request->get('address');
        $model->postal_code = $request->get('postal_code');
        $model->phone_number = $request->get('phone_number');
        $model->fax_number = $request->get('fax_number');
        $model->longitude = $request->get('longitude');
        $model->latitude = $request->get('latitude');
        $model->region_id = $data[0];
        $model->city_id = $request->get('city_id');
        $model->parent_id = $parent_id;
        $model->save();

        $model->party_types()->sync($int_type);
        
        return redirect()->route('parties.index')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Party  $party
     * @return \Illuminate\Http\Response
     */
    public function show(Party $party)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Party  $party
     * @return \Illuminate\Http\Response
     */
    public function edit(Party $party)
    {
        $action = route('parties.update', $party->id);
        $method = "PUT";
        $cities = City::where('province_id', $party->region_id)->pluck('name', 'id');
        $party_types = PartyType::whereNotIn('id',[99])->orderBy('order_item', 'asc')->get();
        //kantor pusat tidak muncul
        $parties = Party::where('parent_id',1)->where('id','<>',1)->get();
        $regions = Region::pluck('name', 'id');
        return view('parties.edit',compact('action','method','party','cities','party_types', 'regions', 'parties'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Party  $party
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Party $party)
    {
        //Validate
        $request->validate([
            'code' => 'required|unique:parties,code,'.$party->id,
            'name' => 'required',
            'address' => 'required',
            'longitude' => 'required',
            'latitude' => 'required',
            'postal_code' => 'required|min:5|integer',
            'phone_number' => 'required|numeric|min:6'
        ]);

        $parent_id = 0;

        //return $request->get('party_types');

        if (in_array('branch', $request->get('party_types'))) {
            $parent_id = 1;
        }
        // if (in_array('Subdivre', $request->get('party_types'))) {
        //     $parent_id = $request->get('parent_id');
        // }

        //return $parent_id;

        $string_type = $request->get('party_types');
        $int_type = array();

        foreach($string_type as $st){
            $single_int_type = PartyType::where('name',$st)->pluck('id');

            array_push($int_type, $single_int_type[0]);
        }

        $data = explode(',',$request->get('region_id'));

        $model = $party;
        $model->code = $request->get('code');
        $model->name = strtoupper($request->get('name'));
        $model->address = $request->get('address');
        $model->postal_code = $request->get('postal_code');
        $model->phone_number = $request->get('phone_number');
        $model->fax_number = $request->get('fax_number');
        $model->longitude = $request->get('longitude');
        $model->latitude = $request->get('latitude');
        $model->region_id = $data[0];
        $model->city_id = $request->get('city_id');
        $model->parent_id = $parent_id;
        $model->save();

        
        $model->party_types()->sync($int_type);
        
        return redirect()->route('parties.index')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Party  $party
     * @return \Illuminate\Http\Response
     */
    public function destroy(Party $party)
    {
        $party->delete();
        return redirect('parties')->with('success','Data berhasil dihapus');
    }

    public function getJson($id)
    {
        $party = Party::findOrFail($id);
        return response()->json($party);
    }

    public function getPartyList()
    {
        $party_type = request('party_type');
        $name = request('name');
        $id = request('id');

        $data = Storage::leftJoin('storage_projects', 'storages.id', 'storage_projects.storage_id')
                        ->leftJoin('warehouses', 'warehouses.id', 'storages.warehouse_id')
                        ->leftJoin('parties', 'warehouses.branch_id', 'parties.id')
                        ->where('storage_projects.project_id', session()->get('current_project')->id)
                        ->whereExists(function($query) use ($party_type){
                            // (select * from `party_types` inner join `parties_party_types` on `party_types`.`id` = `parties_party_types`.`party_type_id` where `parties`.`id` = `parties_party_types`.`party_id` and `name` in (?))
                            $query->selectRaw('COUNT(party_id)')->from('party_types')
                                    ->join('parties_party_types', 'party_types.id', 'parties_party_types.party_type_id')
                                    ->whereRaw('parties.id = parties_party_types.party_id')
                                    ->whereIn('name', explode(',', $party_type))
                                    ->havingRaw('COUNT(party_id) = ' . count(explode(',', $party_type)));
                        })
                        ->where('warehouses.city_id', request('city_id'))
                        ->select(['parties.*'])
                        ->groupBy('parties.id')
                        ->get();

        // return [$data->toSql(), $data->getBindings()];
        // $data = Party::whereHas('party_types', function ($query) use ($party_type) {
        //     /**
        //      * branch / transporter / consignee
        //      */
        //     $query->whereIn('name', explode(',', $party_type));
        // })->where('city_id', request('city_id'))->get();

        if(count($data) === 0) {
            return View::make('html-responses.select-party', [
                'data' => $data,
                'count' => count($data),
                'name' => $name,
                'id' => $id,
                'key' => 'id',
                'label' => 'name'
            ]);
        }

        return View::make('html-responses.select-party', [
            'data' => $data,
            'count' => count($data),
            'name' => $name,
            'id' => $id,
            'key' => 'id',
            'label' => 'name'
        ]);
    }

    public function get_city(Request $request){
        $province_id = $request->input('region_id'); 
        

        if (isset($province_id)) {

            $data = explode(',',$province_id);

            if($data[1] == null || $data[1] == ''){
                $party = null;
            }
            else{
                $party = Party::find($data[1]);
            }
            
            
            $cities = City::where('province_id' , $data[0])->get();

            return view('parties.select_city', compact('cities','party'));
        }       
    }
}
