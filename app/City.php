<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class City extends Model
{
    use LogsActivity;

    protected static $logAttributes = ['name'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cities';

    public $timestamps = false;

    public function warehouses()
    {
        return $this->hasMany('App\Warehouse','city_id','id');
    }

    public function companies()
    {
        return $this->hasMany('App\Company','city_id','id');
    }

    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }

    public function province()
    {
        return $this->belongsTo('App\Region','province_id','id');
    }
}
