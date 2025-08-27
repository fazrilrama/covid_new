<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssignedTo extends Model
{
    protected $table = 'assigned_to';
    protected $guarded = [];

    public function users()
    {
    	return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function projects()
    {
    	return $this->belongsTo('App\Project', 'project_id', 'id');
    }
}
