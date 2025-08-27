<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StorageProjects extends Model
{
    protected static $logAttributes = ['storage_id','project_id'];

    protected $fillable = ['storage_id','project_id'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'storage_projects';

    public function storage()
    {
        return $this->belongsTo('App\Storage','storage_id','id');
    }

    public function project()
    {
        return $this->belongsTo('App\Project','project_id','id');
    }
}
