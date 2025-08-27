<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemProjects extends Model
{
    protected static $logAttributes = ['item_id','project_id'];

    protected $fillable = ['item_id','project_id'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'item_projects';

    public function item()
    {
        return $this->belongsTo('App\Item','item_id','id');
    }

    public function project()
    {
        return $this->belongsTo('App\Project','project_id','id');
    }
}
