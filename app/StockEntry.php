<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class StockEntry extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stock_entries';


    public function stock_deliveries()
    {
        return $this->hasOne('App\StockDelivery','stock_entry_id','id');
    }

    public function details()
    {
        return $this->hasMany('App\StockEntryDetail','stock_entry_id','id')->orderBy('id');
    }

    public function user()
    {
        return $this->belongsTo('App\User','user_id','id');
    }

    public function employee()
    {
        return $this->belongsTo('App\User','employee_id','id');
    }

    public function warehouse()
    {
        return $this->belongsTo('App\Warehouse','warehouse_id','id');
    }

    public function stock_transport()
    {
        return $this->belongsTo('App\StockTransport','stock_transport_id','id');
    }

    public function company()
    {
        return $this->belongsTo('App\Company','company_id','id');
    }

    public function getDocCode($type = 'inbound')
    {
    	return ($type == 'inbound') ? 'PA' : 'PP' ;
    }
    
    public function project()
    {
        return $this->belongsTo('App\Project');
    }

    public function scopeOfStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function getEditableAttribute()
    {
        $editable_statuses = ['Pending','Processed'];
        return $editable = in_array($this->status,$editable_statuses) ? true : false;
    }

}
