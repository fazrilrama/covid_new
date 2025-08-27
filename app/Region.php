<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Region extends Model
{
    use LogsActivity;

    protected static $logAttributes = ['name'];

    public $timestamps = false;

    protected $table = 'provinces';

    public function warehouses()
    {
        return $this->hasMany('App\Warehouse','region_id','id');
    }

    public function party()
    {
        return $this->hasMany('App\Party','region_id','id');
    }
}
