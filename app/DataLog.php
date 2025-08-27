<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataLog extends Model
{
    protected $fillable = ['user_id','type','sub_type','record_id','status'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'data_log';
}
