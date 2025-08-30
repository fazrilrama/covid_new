<?php

namespace App\Http\Controllers;

use App\Warehouse;
use App\Contract;
use App\WarehouseOfficer;
use App\City;
use App\Region;
use App\Party;
use App\User;
use App\PartiesPartyTypes;
use Auth;
use DB;
use Illuminate\Http\Request;
use DataTables;
use Yajra\DataTables\Html\Builder;
use App\Exports\DivreWarehouseExport;
use App\Exports\DivreWarehouseNoCoordinateExport;
use App\Exports\DivreWarehouseNonActiveExport;
use App\Exports\WarehouseExport;
use Excel;
use App\Storage;
use App\TypeWarehouseModel;
use App\AdditionalWarehouseModel;
use App\WarehouseAddMoreModel;
use Illuminate\Support\Facades\Validator;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $branch_id = auth()->user()->branch_id;

        if($branch_id){
            $warehouse = Warehouse::leftJoin('warehouse_type', 'warehouse_type.id', '=', 'warehouses.type_id')
            ->where('branch_id', $branch_id)->get(['warehouses.id','warehouses.code','warehouses.name','warehouses.region_id','warehouses.branch_id','warehouses.length','warehouses.width','warehouses.is_active', 'warehouses.ownership', 'warehouses.status', 'warehouse_type.name as type_warehouse']);
        } else {
            $warehouse = Warehouse::leftJoin('warehouse_type', 'warehouse_type.id', '=', 'warehouses.type_id')
            ->get(['warehouses.id','warehouses.code','warehouses.name','warehouses.region_id','warehouses.branch_id','warehouses.length','warehouses.width','warehouses.total_volume','warehouses.total_weight','warehouses.is_active', 'warehouses.ownership','warehouses.status', 'warehouse_type.name as type_warehouse', 'warehouse_type.color']);
        }

        if ($request->submit) {
            // $warehouses = $warehouse;
            return Excel::download(new WarehouseExport, 'warehouses-'. str_slug(now()) .'.xlsx');
        }
        
        return view('warehouses.index',compact('warehouse'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $action = route('warehouses.store');
        $method = 'POST';
        $cities = City::pluck('name', 'id');
        $regions = Region::pluck('name', 'id');
        // 1. Kalau ada data cabang, filter datanya
        if($branch = Auth::user()->branch) {
            $branches = Party::where('id', $branch->id)->get();
        } else {
            $branches = Party::whereHas('party_types', function ($query) {
                $query->where('name','branch');
            })->get();
        }
        $warehouse = new Warehouse;
        $province = new \StdClass;
        $province->id = NULL;
        $province->name = 'Pilih Cabang/Subcabang';

        return view('warehouses.create',compact('action','method','warehouse','cities','regions','branches', 'province'));
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
            //'code' => 'required|alpha_dash|unique:warehouses,code',
            'code' => 'required|unique:warehouses,code',
            'name' => 'required',
            // 'email' => 'required|email',
            'address' => 'required',
            'postal_code' => 'required',
            'phone_number' => 'required',
            'total_weight' => 'numeric',
            'total_volume' => 'numeric',
            'length' => 'numeric',
            'width' => 'numeric',
            'tall' => 'numeric',
            'branch_id' => 'required'
        ]);

        $model = new Warehouse;
        $model->code = $request->get('code');
        $model->name = strtoupper($request->get('name'));
        // $model->email = $request->get('email');
        $model->address = $request->get('address');
        $model->postal_code = $request->get('postal_code');
        $model->phone_number = $request->get('phone_number');
        $model->fax_number = $request->get('fax_number');
        $model->city_id = $request->get('city_id');
        $model->region_id = $request->get('region_id');
        $model->branch_id = $request->get('branch_id');
        $model->total_weight = $request->get('total_weight');
        $model->total_volume = $request->get('total_volume');
        $model->length = $request->get('length');
        $model->width = $request->get('width');
        $model->tall = $request->get('tall');
        $model->ownership = $request->get('ownership');
        $model->is_active = $request->get('is_active');
        $model->latitude = $request->get('latitude');
        $model->longitude = $request->get('longitude');
        $model->percentage_buffer = $request->get('percentage_buffer');

        // $model->longitude = $request->get('stauts');
        $model->save();

        return redirect()->route('warehouses.index')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function show(Warehouse $warehouse)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function edit(Warehouse $warehouse)
    {
        $action = route('warehouses.update', $warehouse->id);
        $action_add = route('warehouses.update_add', $warehouse->id);
        $method = "PUT";
        $province = Region::find($warehouse->region_id);
        $regions = Region::pluck('name', 'id');
        $cities = City::where('province_id', $warehouse->region_id)->pluck('name', 'id');
        // 1. Kalau ada data cabang, filter datanya
        if($branch = Auth::user()->branch) {
            $branches = Party::where('id', $branch->id)->get();
        } else {
            $branches = Party::whereHas('party_types', function ($query) {
                            $query->where('name','branch');
                        })->get();
        }

        $warehouse_type = TypeWarehouseModel::get();
        $warehouse_add = AdditionalWarehouseModel::get();

        $selected = WarehouseAddMoreModel::where('warehouse_id', $warehouse->id)
            ->pluck('add_id') // ganti add_id sesuai nama kolom relasi
            ->toArray();

        return view('warehouses.edit',compact('action','method','warehouse','cities','regions','branches', 'province', 'warehouse_type', 'warehouse_add', 'action_add', 'selected'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */

    public function update_add(Request $request, $id) {
        $data = $request->all();

        DB::beginTransaction();

        try {

            $check = WarehouseAddMoreModel::where('warehouse_id', $id)->get();

            if ($check->count() > 0) {
                foreach ($check as $item) {
                    $item->delete();
                }
            }

            for ($i=0; $i < count($data['warehouse_add']); $i++) { 
                $element = $data['warehouse_add'][$i];

                WarehouseAddMoreModel::create([
                    "add_id" => $element,
                    "warehouse_id" => $id
                ]);
            }

            DB::commit();
            return redirect()->route('warehouses.index')->with('success', 'Data berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('warehouses.index')->with('error', 'Data gagal disimpan');
        }

    }

    public function update(Request $request, Warehouse $warehouse)
    {
        //Validate
        $request->validate([
            'code' => 'required|unique:warehouses,code,'.$warehouse->id,
            'name' => 'required',
            // 'email' => 'required|email',
            'address' => 'required',
            'postal_code' => 'required',
            'phone_number' => 'required',
            'total_weight' => 'numeric',
            'total_volume' => 'numeric',
            'length' => 'numeric',
            'width' => 'numeric',
            'tall' => 'numeric',
            'branch_id' => 'required',
            'warehouse_type' => 'required'
        ]);

        $model = $warehouse;
        $model->code = $request->get('code');
        $model->name = strtoupper($request->get('name'));
        // $model->email = $request->get('email');
        $model->address = $request->get('address');
        $model->postal_code = $request->get('postal_code');
        $model->phone_number = $request->get('phone_number');
        $model->fax_number = $request->get('fax_number');
        $model->city_id = $request->get('city_id');
        $model->region_id = $request->get('region_id');
        $model->branch_id = $request->get('branch_id');
        $model->total_weight = $request->get('total_weight');
        $model->total_volume = $request->get('total_volume');
        $model->length = $request->get('length');
        $model->width = $request->get('width');
        $model->tall = $request->get('tall');
        $model->ownership = $request->get('ownership');
        $model->is_active = $request->get('is_active');
        $model->latitude = $request->get('latitude');
        $model->longitude = $request->get('longitude');
        $model->status = $request->get('status');
        $model->type_id = $request->get('warehouse_type');
        $model->percentage_buffer = $request->get('percentage_buffer');

        $model->save();

        return redirect()->route('warehouses.index')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function destroy(Warehouse $warehouse)
    {
        if($warehouse->storages->count()>0) {
            return redirect('warehouses')->with('message','Data tidak bisa dihapus karena masih ada storage');
        } 
        elseif($warehouse->warehouse_officer->count()>0){
            return redirect('warehouses')->with('message','Data tidak bisa dihapus karena masih ada warehouse officer');
        }
        else {
            $warehouse->delete();
            return redirect('warehouses')->with('success','Data berhasil dihapus');
        }
    }

    public function maps(Request $request)
    {
        $selectedRegion = $request->input('region');
        $regionConditions = [];
        if (!empty($selectedRegion)) {
            $regionConditions = ['region_id' => $selectedRegion];
        }

        $regions = Region::get(['id', 'name']);
		/*
			$warehouseLocations = Warehouse::whereNotNull('latitude')
            ->where('is_active', 1)
            ->whereNotNull('longitude')
            ->where($regionConditions)
            ->join('cities as c', 'c.id', '=', 'warehouses.city_id')
            ->join('provinces as p', 'p.id', '=', 'warehouses.region_id')
            ->get([
                'warehouses.id',
                'warehouses.code',
                'c.name as area',
                'p.name as region',
                'warehouses.total_weight as total_volume',
                'warehouses.name',
                'warehouses.latitude',
                'warehouses.length',
                'warehouses.width',
                'warehouses.longitude',
                DB::raw('(SELECT type FROM stock_entry_details AS sed
                            JOIN stock_entries AS se ON se.id = sed.stock_entry_id
                            JOIN storages ON storages.id = sed.storage_id
                            WHERE storages.warehouse_id = warehouses.id
                            ORDER BY sed.created_at DESC
                            LIMIT 1) as last_activity'),
                DB::raw('(SELECT sed.created_at FROM stock_entry_details AS sed
                            JOIN stock_entries AS se ON se.id = sed.stock_entry_id
                            JOIN storages ON storages.id = sed.storage_id
                            WHERE storages.warehouse_id = warehouses.id
                            ORDER BY sed.created_at DESC
                            LIMIT 1) as last_activity_date'),
            ]);
              

        $count = 0;
        $count2 = 0;
        $wh1 = array();
        foreach ($warehouseLocations as $wh) {

            $wh->total_space = $wh->length*$wh->width;
            $wh->total_rented_space = 0;
            $wh->utility_space = 0;
            foreach ($wh->contracts()->where('is_active', 1)->get() as $contract) {
                $wh->total_rented_space += $contract->pivot->rented_space;
            }

            if($wh->length == 0 || $wh->width ==0){
                $wh->utility_space = 99999999;
                $count +=1;
                array_push($wh1, $wh);
            }else{
                if (empty($wh->total_rented_space)) {
                    $count +=1;
                    $wh->utility_space = 0;
                    array_push($wh1, $wh);
                }else{
                    $wh->utility_space = ($wh->total_rented_space/$wh->total_space)*100;
                    // $wh->utility_space = round(($wh->total_rented_space/
                    // $wh->total_space)*100,2);
                    if ($wh->utility_space >0) {
                        # code...
                    $count2 += 1;
                    }
                    // $wh1 = $wh;
                    array_push($wh1, $wh);
                }
            }
        }
        // dd($wh1);
        // dd($count2);
        // dd($warehouseLocations->first());*/

        
        return view('warehouses.maps', compact('selectedRegion', 'regions'));
    }

	public function maps_api(Request $request){
		$selectedRegion = $request->input('region');
        $regionConditions = [];
        if (!empty($selectedRegion)) {
            $regionConditions = ['region_id' => $selectedRegion];
        }

        $regions = Region::get(['id', 'name']);

        $warehouseLocations = Warehouse::select([
                'warehouses.id',
                'warehouses.code',
                'c.name as area',
                'p.name as region',
                'warehouses.total_weight as total_volume',
                'warehouses.name',
                'warehouses.latitude',
                'warehouses.length',
                'warehouses.width',
                'warehouses.longitude',
                'parties.name as divre'
            ])
            ->whereNotNull('warehouses.latitude')
            ->where('is_active', 1)
            ->whereNotNull('warehouses.longitude')
            ->where('branch_id', '32')
            ->whereIn('warehouses.id', ['892','1385', '888'])
            ->where($regionConditions)
            ->join('cities as c', 'c.id', '=', 'warehouses.city_id')
            ->join('provinces as p', 'p.id', '=', 'warehouses.region_id')
            ->leftJoin('parties', 'parties.id', 'warehouses.branch_id')
            ->get();
		//PR
		/*
				,DB::raw('(SELECT type FROM stock_entry_details AS sed
                            JOIN stock_entries AS se ON se.id = sed.stock_entry_id
                            JOIN storages ON storages.id = sed.storage_id
                            WHERE storages.warehouse_id = warehouses.id
                            ORDER BY sed.created_at DESC
                            LIMIT 1) as last_activity'),
                DB::raw('(SELECT sed.created_at FROM stock_entry_details AS sed
                            JOIN stock_entries AS se ON se.id = sed.stock_entry_id
                            JOIN storages ON storages.id = sed.storage_id
                            WHERE storages.warehouse_id = warehouses.id
                            ORDER BY sed.created_at DESC
                            LIMIT 1) as last_activity_date'),*/
              

        $count = 0;
        $count2 = 0;
        $wh1 = array();
        foreach ($warehouseLocations as $wh) {

            $wh->total_space = $wh->length*$wh->width;
            $wh->total_rented_space = 0;
            $wh->utility_space = 0;
            foreach ($wh->contracts()->where('is_active', 1)->get() as $contract) {
                $wh->total_rented_space += $contract->pivot->rented_space;
            }

            if($wh->length == 0 || $wh->width ==0){
                $wh->utility_space = 99999999;
                $count +=1;
                array_push($wh1, $wh);
            }else{
                if (empty($wh->total_rented_space)) {
                    $count +=1;
                    $wh->utility_space = 0;
                    array_push($wh1, $wh);
                }else{
                    $wh->utility_space = ($wh->total_rented_space/$wh->total_space)*100;
                    // $wh->utility_space = round(($wh->total_rented_space/
                    // $wh->total_space)*100,2);
                    if ($wh->utility_space >0) {
                        # code...
                    $count2 += 1;
                    }
                    // $wh1 = $wh;
                    array_push($wh1, $wh);
                }
            }
        }
		
        // dd($warehouseLocations);
        // dd($count2);
        // dd($warehouseLocations->first());
		
		return (['total_data'=>count($warehouseLocations),'location'=>$warehouseLocations,'whl'=>($wh1)]);
	}
	
	public function maps_api_activity(Request $req){
		$id=$req->input('id');
		$is_array=$req->input('is_array');
		$is_array=$is_array!=null?$is_array:false;
		if($id!="" && $id!=null && !$is_array){
			$last_activity=DB::select("SELECT type FROM stock_entry_details AS sed
								JOIN stock_entries AS se ON se.id = sed.stock_entry_id
								JOIN storages ON storages.id = sed.storage_id
								WHERE storages.warehouse_id = ?
                                and se.project_id in ('334','333','332', '336')
                                and se.warehouse_id in ('892','1385', '888')
								ORDER BY sed.created_at DESC
								LIMIT 1",[$id]);
			$last_activity_date=DB::select("SELECT sed.created_at FROM stock_entry_details AS sed
								JOIN stock_entries AS se ON se.id = sed.stock_entry_id
								JOIN storages ON storages.id = sed.storage_id
								WHERE storages.warehouse_id = ?
                                and se.project_id in ('334','333','332', '336')
                                and se.warehouse_id in ('892','1385', '888')
								ORDER BY sed.created_at DESC
								LIMIT 1",[$id]);
			$last_activity=$last_activity!=null&&count($last_activity)>0?$last_activity[0]->type:'-';
			$last_activity_date=$last_activity_date!=null&&count($last_activity_date)>0?$last_activity_date[0]->created_at:'-';
			return (['id'=>$id,'last_activity'=>$last_activity,'last_activity_date'=>$last_activity_date]);
		}
		else if($is_array){
			$ids=explode(',',$id);
			$data=[];
			
			$j=0;
			foreach($ids as $i)
			{
				
				$last_activity=DB::select("SELECT type FROM stock_entry_details AS sed
									JOIN stock_entries AS se ON se.id = sed.stock_entry_id
									JOIN storages ON storages.id = sed.storage_id
									WHERE storages.warehouse_id = ?
                                    and se.project_id in ('334','333','332','336')
                                    and se.warehouse_id in ('892','1385', '888')
									ORDER BY sed.created_at DESC
									LIMIT 1",[$i]);
				$last_activity_date=DB::select("SELECT sed.created_at FROM stock_entry_details AS sed
									JOIN stock_entries AS se ON se.id = sed.stock_entry_id
									JOIN storages ON storages.id = sed.storage_id
									WHERE storages.warehouse_id = ?
                                    and se.project_id in ('334','333','332','336')
                                    and se.warehouse_id in ('892','1385', '888')
									ORDER BY sed.created_at DESC
									LIMIT 1",[$i]);
				$last_activity=$last_activity!=null&&count($last_activity)>0?$last_activity[0]->type:'-';
				$last_activity_date=$last_activity_date!=null&&count($last_activity_date)>0?$last_activity_date[0]->created_at:'-';
				
				$data[$j]['id']=$i;
				$data[$j]['last_activity']=$last_activity;
				$data[$j]['last_activity_date']=$last_activity_date;
				$j++;
			
			}
			return ($data);
		}
		else{
            $activity=Warehouse::whereNotNull('latitude')
            ->where('branch_id', '32')
            ->whereIn('id', ['892','1385', '888'])
            ->whereNotNull('longitude')
            ->where('is_active', 1)
            ->with('branch')
			->get();
			/*foreach($activity as $act){
				$last_activity=DB::select("SELECT type FROM stock_entry_details AS sed
								JOIN stock_entries AS se ON se.id = sed.stock_entry_id
								JOIN storages ON storages.id = sed.storage_id
								WHERE storages.warehouse_id = ?
								ORDER BY sed.created_at DESC
								LIMIT 1",[$act->id]);
				$last_activity=$last_activity!=null&&count($last_activity)>0?$last_activity[0]->type:'-';
				$act->last_activity=$last_activity;
				
				$last_activity_date=DB::select("SELECT sed.created_at FROM stock_entry_details AS sed
								JOIN stock_entries AS se ON se.id = sed.stock_entry_id
								JOIN storages ON storages.id = sed.storage_id
								WHERE storages.warehouse_id = ?
								ORDER BY sed.created_at DESC
								LIMIT 1",[$act->id]);
				$last_activity_date=$last_activity_date!=null&&count($last_activity_date)>0?$last_activity_date[0]->created_at:'-';
				$act->last_activity_date=$last_activity_date;
			}*/
			return (['total_data'=>count($activity),'wh'=>$activity]);
		}
	}
    
	public function activity_table(Request $req){
		$start=$req->input('start');
		$length=$req->input('length');
		$search=$req->input('search[value]');
		$sort_column=$req->input('order[0][column]');
		$sort_rule=$req->input('order[0][dir]');
		
		$start=$start!=null?$start:0;
		$length=$length!=null?$length:10;
		$total=Warehouse::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where('is_active', 1)
			->count();
		
		$activity=DB::select(" select * from(select a.*,(SELECT type FROM stock_entry_details AS sed
                            JOIN stock_entries AS se ON se.id = sed.stock_entry_id
                            JOIN storages ON storages.id = sed.storage_id
                            WHERE storages.warehouse_id = a.id
                            ORDER BY sed.created_at DESC
                            LIMIT 1) as last_activity
							,(SELECT sed.created_at FROM stock_entry_details AS sed
                            JOIN stock_entries AS se ON se.id = sed.stock_entry_id
                            JOIN storages ON storages.id = sed.storage_id
                            WHERE storages.warehouse_id = a.id
                            and se.project_id in ('334','333','332')
                            ORDER BY sed.created_at DESC
                            LIMIT 1) as last_activity_date
						from warehouses a where a.is_active=1 and a.latitude is not null and a.longitude is not null
						) as data order by last_activity_date desc limit $start,$length");
							
		return ['status'=>true,'showedData'=>count($activity),'recordsFiltered'=>$total,'recordsTotal'=>$total,'data'=>$activity];
	}
	
	public function get_region_city(Request $request){
        $party_id = $request->input('party_id'); 

        if (isset($party_id)) {
            
            $party = Party::find($party_id);
            $province = Region::find($party->region_id);
            $cities = City::where('province_id' , $province->id)->get();
            $regions = Region::all();

            return view('warehouses.select_region_city', compact('regions', 'province','cities'));
        }       
    }

    public function to_add_user(Warehouse $warehouse){
        $action = route('add_user', $warehouse->id);
        $method = "POST";
        $party = Party::find($warehouse->branch_id);
        

        $users = User::where('branch_id',$warehouse->branch_id)
                ->get();


        $warehouse_officers = WarehouseOfficer::where('warehouse_id' ,$warehouse->id)->get();

        $user_officer_supervisor = array();

        foreach($users as $user){    
            //cek apakah user ini sudah ada di assign di warehouse tertentu apa tidak
            $check_warehouse_officer = WarehouseOfficer::where('user_id',$user->id)
                                        // ->groupBy('user_id')
                                        ->get(['user_id','warehouse_id']);

            //return $check_warehouse_officer->count();

            if($check_warehouse_officer->count() > 0){
                foreach($check_warehouse_officer as $c){
                    

                    if(!$user->warehouse_name){
                        $user->warehouse_name = $c->warehouse['name'];
                    }
                    elseif($user->warehouse_name){
                        $user->warehouse_name = $user->warehouse_name.','.$c->warehouse['name'];
                    }
                }   
            }
            else{
                $user->warehouse_name = 'free';
            }  
            
            //hanya supervisor dan officer yg bisa di assign ke warehouse
            if($user->hasRole('WarehouseSupervisor') || $user->hasRole('WarehouseOfficer')){
                $user->role = $user->roles->first()->name;
                array_push($user_officer_supervisor, $user);
            }
            
        }

        return view('warehouses.add_user',compact('action','method','warehouse','party','users','warehouse_officers','user_officer_supervisor'));
    }

    public function add_user(Request $request){

        $warehouse = Warehouse::find($request->input('warehouse_id'));
        $user = User::find($request->input('user_id'));

        $warehouse->User()->syncWithoutDetaching($user->id);

        return redirect()->route('to_add_user', $warehouse)->with('success', 'Data berhasil disimpan');
    }

    public function delete_user(Request $request){
        $WarehouseOfficer = WarehouseOfficer::find($request->input('warehouse_officer_id'));
        $warehouse = Warehouse::find($request->input('warehouse_id'));
        
        $WarehouseOfficer->delete();

        return redirect()->route('to_add_user', $warehouse)->with('success', 'Data berhasil dihapus');
    }

    public function getList()
    {
        $party = Party::find(request('shipper_id'));
        $contract = Contract::find(request('contract_id'));

        $warehouses = Storage::join('warehouses as w', 'w.id', '=', 'storages.warehouse_id')
                ->join('contract_warehouse', 'contract_warehouse.warehouse_id', '=', 'w.id')
                ->join('contracts', 'contracts.id', '=', 'contract_warehouse.contract_id')
                ->where('contracts.end_date', '>=', now()->format('Y-m-d'))
                ->where('w.branch_id', $party->id)
                ->where('contracts.id', $contract->id)
                ->where('contracts.project_id', session()->get('current_project')->id); 

            $warehouses = $warehouses->groupBy('w.id')->get([
                    'w.id',
                    'w.name',
                    'w.code',
                ]);
                

        // $warehouses = Warehouse::where('branch_id', $party->id)->get();
        return view('warehouses.select_warehouses', compact('warehouses'));
    }

    public function exportWarehouse()
    {
        return Excel::download(new DivreWarehouseExport, 'warehouses-'. str_slug(now()) .'.xlsx');
    }

    public function exportNoCoordinateWarehouse()
    {
        return Excel::download(new DivreWarehouseNoCoordinateExport, 'warehouses-no-coordinate-'. str_slug(now()) .'.xlsx');
    }

    public function exportNonActiveWarehouse()
    {
        return Excel::download(new DivreWarehouseNonActiveExport, 'warehouses-non-active-'. str_slug(now()) .'.xlsx');
    }
}
