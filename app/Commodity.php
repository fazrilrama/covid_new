<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Commodity extends Model
{
    use LogsActivity;

    protected static $logAttributes = ['name','code'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'commodities';

    public function storages()
    {
        return $this->hasMany('App\Storage','commodity_id','id');
    }

    public function items()
    {
        return $this->hasMany('App\Item','commodity_id','id');
    }

    public function packings()
    {
        return $this->hasMany('App\Packing','commodity_id','id');
    }

}
