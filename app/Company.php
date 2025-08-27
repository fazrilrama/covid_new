<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Company extends Model
{
    use LogsActivity;

    protected static $logAttributes = ['name','code','address','postal_code','phone_number','fax_number','city_id','company_id'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'companies';

    public function company_type()
    {
        return $this->belongsTo('App\CompanyType','company_type_id','id');
    }

    public function city()
    {
        return $this->belongsTo('App\City','city_id','id');
    }

    // public function users()
    // {
    //     return $this->hasMany('App\User','company_id','id');
    // }

    public function items()
    {
        return $this->hasMany('App\Item','company_id','id');
    }

    public function advance_notices()
    {
        return $this->hasMany('App\AdvanceNotice','company_id','id');
    }

    public function stock_transports()
    {
        return $this->hasMany('App\StockTransport','company_id','id');
    }

    public function stock_entries()
    {
        return $this->hasMany('App\StockEntry','company_id','id');
    }

    public function stock_deliveries()
    {
        return $this->hasMany('App\StockDelivery','company_id','id');
    }

    public function warehouses()
    {
        return $this->hasMany('App\Warehouse','company_id','id');
    }

    public function projects()
    {
        return $this->hasMany('App\Project','company_id','id');
    }
    
}
