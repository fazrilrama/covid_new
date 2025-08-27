<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdvanceNotice extends Model
{
    use LogsActivity;

    protected $table = 'stock_advance_notices';

    protected static $logAttributes = ['sptb_num', 'sppk_num', 'sptb_doc', 'sppk_doc', 'etd','eta','origin','destination','waybill','ref_code','vehicle_code_num','vehicle_plate_num','shipper_address','consignee_address','head_ds','contractor','annotation','is_arrived','employee_name'];

    public function transport_type()
    {
        return $this->belongsTo('App\TransportType','transport_type_id','id');
    }

    public function details()
    {
        return $this->hasMany('App\AdvanceNoticeDetail','stock_advance_notice_id','id');
    }

    public function transports()
    {
        return $this->hasMany('App\StockTransport','advance_notice_id','id');
    }

    public function origin()
    {
        return $this->belongsTo('App\City','origin_id','id');
    }

    public function destination()
    {
        return $this->belongsTo('App\City','destination_id','id');
    }

    public function user()
    {
        return $this->belongsTo('App\User','user_id','id');
    }

    public function getDocCode($type = 'inbound')
    {
        return ($type == 'inbound') ? 'AIN' : 'AON' ;
    }

    public function getDocStoCode($type = 'inbound')
    {
        return ($type == 'inbound') ? 'STOI' : 'STOO' ;
    }

    public function activity()
    {
        return $this->belongsTo('App\AdvanceNoticeActivity','advance_notice_activity_id','id');
    }

    public function warehouse()
    {
        return $this->belongsTo('App\Warehouse', 'warehouse_id', 'id');
    }
    
    public function ownership()
    {
        return $this->belongsTo('App\Party','owner','id');
    }
    
    public function project()
    {
        return $this->belongsTo('App\Project', 'project_id', 'id');
    }

    public function shipper()
    {
        return $this->belongsTo('App\Party','shipper_id','id');
    }

    public function consignee()
    {
        return $this->belongsTo('App\Party','consignee_id','id');
    }

    public function branch()
    {
        if($this->type=='inbound') {
            return $this->belongsTo('App\Party', 'consignee_id', 'id');
        }else {
            return $this->belongsTo('App\Party', 'shipper_id', 'id');
        }
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

    public function advance_notice_activity()
    {
        return $this->belongsTo('App\AdvanceNoticeActivity','advance_notice_activity_id','id');
    }
    
    public function getDocMovementCode($type = 'inbound')
    {
        return ($type == 'inbound') ? 'IMTI' : 'IMTO' ;
    }
}
