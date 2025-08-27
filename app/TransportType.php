<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class TransportType extends Model
{
    use LogsActivity;

    protected static $logAttributes = ['name'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transport_types';

    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }
}
