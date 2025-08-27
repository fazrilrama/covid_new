<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class UomConversion extends Model
{
    use LogsActivity;

    protected static $logAttributes = ['from_uom_id','to_uom_id','multiplier'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'uom_conversions';

    public function from_uom()
    {
        return $this->belongsTo('App\Uom','from_uom_id','id');
    }

    public function to_uom()
    {
        return $this->belongsTo('App\Uom','to_uom_id','id');
    }
}
