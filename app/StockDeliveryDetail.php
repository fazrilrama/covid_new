<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class StockDeliveryDetail extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stock_delivery_details';

    public function header()
    {
        return $this->belongsTo('App\StockDelivery','stock_delivery_id','id');
    }

    public function item()
    {
        return $this->belongsTo('App\Item','item_id','id');
    }

    public function uom()
    {
        return $this->belongsTo('App\Uom','uom_id','id');
    }
}
