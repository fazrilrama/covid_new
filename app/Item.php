<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Item extends Model
{
    use LogsActivity;

    // protected static $logAttributes = ['type_id','packing_id','sku','name','description','length','width','height','weight','volume','min_qty','default_uom_id','control_method_id','commodity_id','additional_reference'];

    protected $fillable = ['type_id','packing_id','sku','name','description','handling_tarif','length','width','height','weight','volume','min_qty','default_uom_id','control_method_id','commodity_id','additional_reference'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'items';

    public function project()
    {
        return $this->belongsToMany('App\Project','item_projects','item_id','project_id');
    }

    public function control_method()
    {
        return $this->belongsTo('App\ControlMethod','control_method_id','id');
    }

    public function commodity()
    {
        return $this->belongsTo('App\Commodity','commodity_id','id');
    }

    public function default_uom()
    {
        return $this->belongsTo('App\Uom','default_uom_id','id');
    }

    public function company()
    {
        return $this->belongsTo('App\Company','company_id','id');
    }

    public function type()
    {
        return $this->belongsTo('App\Type','type_id','id');
    }

    // public function stocks()
    // {
    //     return $this->hasMany('App\StockAllocation','item_id');
    // }

    public function item_projects()
    {
        return $this->hasMany('App\ItemProjects','item_id','id');
    }

    public function advance_notice_details()
    {
        return $this->hasMany('App\AdvanceNoticeDetail','item_id');
    }

    public function stock_transport_details()
    {
        return $this->hasMany('App\StockTransportDetail','item_id');
    }

    public function stock_entry_details()
    {
        return $this->hasMany('App\StockEntryDetail','item_id');
    }

    public function stock_delivery_details()
    {
        return $this->hasMany('App\StockDeliveryDetail','item_id');
    }

    public function user(){
        
    }

    public function getStockOnhandAttribute()
    {
        $total_stock = 0;
        foreach ($this->stocks as $stock) { $total_stock += $stock->qty_onhand; }
        return $total_stock;
    }

    public function getStockAllocatedAttribute()
    {
        $total_stock = 0;
        foreach ($this->stocks as $stock) { $total_stock += $stock->qty_allocated; }
        return $total_stock;
    }

    public function getStockAvailableAttribute()
    {
        $total_stock = 0;
        foreach ($this->stocks as $stock) { $total_stock += $stock->qty_available; }
        return $total_stock;
    }
}
