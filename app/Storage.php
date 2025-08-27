<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Storage extends Model
{
    use LogsActivity;

    protected static $logAttributes = ['code','row','column','level','warehouse_id','volume','weight','length','width','height','commodity_id','status'];

    protected $fillable = ['code','row','column','level','warehouse_id','volume','weight','length','width','height','commodity_id','is_active','is_quarantine','is_available','status'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'storages';

    public function project()
    {
        return $this->belongsToMany('App\Project','storage_projects','storage_id','project_id');
    }

    public function warehouse()
    {
        return $this->belongsTo('App\Warehouse','warehouse_id','id');
    }

    public function commodity()
    {
        return $this->belongsTo('App\Commodity','commodity_id','id');
    }

    public function stock_entry_detail()
    {
        return $this->hasMany('App\StockEntryDetail','storage_id','id');
    }

    public function stocks()
    {
        return $this->hasMany('App\StockAllocation','storage_id','id');
    }

    public function storage_projects()
    {
        return $this->hasMany('App\StorageProjects','storage_id','id');
    }

    public function stock_allocations()
    {
        return $this->hasMany('App\StockAllocation','storage_id','id');
    }
}
