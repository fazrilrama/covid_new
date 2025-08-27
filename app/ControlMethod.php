<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class ControlMethod extends Model
{
     use LogsActivity;

    protected static $logAttributes = ['name','description'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'control_methods';

    protected $fillable = [
        'name', 'description'
    ];

    public function items()
    {
        return $this->hasMany('App\Item','control_method_id','id');
    }
}
