<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Project extends Model
{
    use LogsActivity;

    protected static $logAttributes = ['name','description','company_id'];

    protected $table = 'projects';

    public function item()
    {
        return $this->belongsToMany('App\Item','item_projects','project_id','item_id');
    }

    public function storage()
    {
        return $this->belongsToMany('App\Storage','storage_projects','project_id','storage_id');
    }

    public function users()
    {
      return $this->belongsToMany('App\User','assigned_to','project_id','user_id');
    }

    public function company()
    {
      return $this->belongsTo('App\Company','company_id','id');
    }

    public function advancenotices()
    {
      return $this->hasMany('App\AdvanceNotice');
    }

    public function stockdeliveries()
    {
      return $this->hasMany('App\StockDelivery');
    }

    public function stocktransports()
    {
      return $this->hasMany('App\StockTransport');
    }

    public function contract()
    {
        return $this->hasMany('App\Contract','project_id','id');
    }

    public function stocks()
    {
        return $this->hasMany('App\StockAllocation','project_id');
    }

    public function storage_projects()
    {
        return $this->hasMany('App\StorageProjects','project_id','id');
    }

    public function item_projects()
    {
        return $this->hasMany('App\ItemProjects','project_id','id');
    }

}
