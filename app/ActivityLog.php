<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'activity_log';
    protected $guarded = [];

    public function user()
    {
    	return $this->belongsTo('App\User', 'subject_id', 'id');
    }
}
