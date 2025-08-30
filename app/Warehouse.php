<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Warehouse extends Model
{
    use LogsActivity;

    protected static $logAttributes = ['name','email','code','address','phone_number','fax_number','postal_code','city_id','region_id','company_id','total_volume','total_weight','length','width','tall','ownership','longitude','latitude', 'branch', 'type_id'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'warehouses';

    public function User()
    {
        return $this->belongsToMany('App\User','warehouse_officer','warehouse_id','user_id');
    }

    public function warehouse_officer()
    {
        return $this->hasMany('App\WarehouseOfficer','warehouse_id','id');
    }

    public function storages()
    {
        return $this->hasMany('App\Storage','warehouse_id','id');
    }

    public function advance_notice()
    {
        return $this->hasMany('App\AdvanceNotice','warehouse_id','id');
    }

    public function stock_entries()
    {
        return $this->hasMany('App\StockEntry','warehouse_id','id');
    }

    public function stock_transports()
    {
        return $this->hasMany('App\StockTransport','warehouse_id','id');
    }
    
    public function city()
    {
        return $this->belongsTo('App\City','city_id','id');
    }

    public function branch()
    {
        return $this->belongsTo('App\Party','branch_id','id');
    }

    public function region()
    {
        return $this->belongsTo('App\Region','region_id','id');
    }

    public function contracts()
    {
        return $this->belongsToMany('App\Contract','contract_warehouse','warehouse_id','contract_id')->withPivot('rented_space');
    }
}
