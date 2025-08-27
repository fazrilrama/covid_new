<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class StockDelivery extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stock_deliveries';

    public function stock_entry()
    {
        return $this->belongsTo('App\StockEntry','stock_entry_id','id');
    }

    public function details()
    {
        return $this->hasMany('App\StockDeliveryDetail','stock_delivery_id','id');
    }

    public function transport_type()
    {
        return $this->belongsTo('App\TransportType','transport_type_id','id');
    }

    public function user()
    {
        return $this->belongsTo('App\User','user_id','id');
    }

    public function company()
    {
        return $this->belongsTo('App\Company','company_id','id');
    }

    public function getDocCode($type = 'inbound')
    {
    	return ($type == 'inbound') ? 'DR' : 'DN' ; // DR: Delivery Receipt, DN: Delivery Note
    }

    public function stock_transport()
    {
        return $this->belongsTo('App\StockTransport','stock_transport_id','id');
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

    public function shipper()
    {
        return $this->belongsTo('App\Party','shipper_id','id');
    }

    public function consignee()
    {
        return $this->belongsTo('App\Party','consignee_id','id');
    }

    public function scopeOfStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
