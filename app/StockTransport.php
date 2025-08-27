<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class StockTransport extends Model
{
    use LogsActivity;

    protected static $logAttributes = ['etd','eta','origin','destination','ref_code','vehicle_code_num','vehicle_plate_num','shipper_address','shipper_postal_code','consignee_address','consignee_postal_code','employee_name','annotation','contractor','head_ds',
    'do_number',
    'do_attachment',
    'driver_name',
    'driver_phone',
    'wp_number',
    'police_number',
    'lhpk_number',
    'lhpk_issue_date',
    'fleet_arrived',
    'unloading_start',
    'unloading_end',
];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stock_transports';

    public function transport_type()
    {
        return $this->belongsTo('App\TransportType','transport_type_id','id');
    }

    public function warehouse()
    {
        return $this->belongsTo('App\Warehouse','warehouse_id','id');
    }

    public function details()
    {
        return $this->hasMany('App\StockTransportDetail','stock_transport_id','id');
    }

    public function user()
    {
        return $this->belongsTo('App\User','user_id','id');
    }

    public function shipper()
    {
        return $this->belongsTo('App\Party','shipper_id','id');
    }

    public function consignee()
    {
        return $this->belongsTo('App\Party','consignee_id','id');
    }

    public function advance_notice()
    {
        return $this->belongsTo('App\AdvanceNotice','advance_notice_id','id');
    }

    

    public function getDocCode($type = 'inbound')
    {
    	return ($type == 'inbound') ? 'GR' : 'DP' ;
    }
    
    public function project()
    {
        return $this->belongsTo('App\Project');
    }

    public function origin()
    {
        return $this->belongsTo('App\City','origin_id','id');
    }

    public function destination()
    {
        return $this->belongsTo('App\City','destination_id','id');
    }

    public function scopeOfStatus($query, $status)
    {
        return $query->whereIn('status', $status);
    }

    public function stock_entries()
    {
        return $this->hasOne('App\StockEntry','stock_transport_id','id');
    }

    public function getEditableAttribute()
    {
        $editable_statuses = ['Pending','Processed'];
        return $editable = in_array($this->status,$editable_statuses) ? true : false;
        dd($this->status);
    }
}
