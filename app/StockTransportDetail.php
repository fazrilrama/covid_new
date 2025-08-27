<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class StockTransportDetail extends Model
{
    protected $guarded = [];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stock_transport_details';
    protected $fillable = ['item_id', 'uom_id', 'qty', 'plan_qty', 'ref_code', 'weight', 'volume', 'plan_qty', 'plan_weight', 'plan_volume', 'control_date'];

    // $fillable = ['storage_id','project_id'];

    public function header()
    {
        return $this->belongsTo('App\StockTransport','stock_transport_id','id');
    }

    public function item_weigher()
    {
        return $this->hasMany('App\ItemWeigher', 'stock_transport_detail_id', 'id');
    }

    public function item()
    {
        return $this->belongsTo('App\Item','item_id','id');
    }

    public function uom()
    {
        return $this->belongsTo('App\Uom','uom_id','id');
    }

    public function control_method()
    {
        return $this->belongsTo('App\ControlMethod', 'control_method_id', 'id');
    }
}
