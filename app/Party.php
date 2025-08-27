<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Party extends Model
{
    use LogsActivity;

    protected static $logAttributes = ['parent_id','name','address','postal_code','phone_number','fax_number','region_id','city_id'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'parties';

    public function party_types()
    {
        return $this->belongsToMany('App\PartyType','parties_party_types','party_id','party_type_id');
    }

    public function parties_party_types()
    {
        return $this->hasMany('App\PartiesPartyTypes','party_id','id');
    }

    public function city()
    {
        return $this->belongsTo('App\City','city_id','id');
    }

    public function region()
    {
        return $this->belongsTo('App\Region','region_id','id');
    }

    public function stocks()
    {
        return $this->hasMany('App\StockAllocation','item_id');
    }

    public function warehouses()
    {
        return $this->hasMany('App\Warehouse','branch_id','id');
    }
    
    public function user()
    {
        return $this->hasMany('App\User', 'branch_id', 'id');
    }
}
