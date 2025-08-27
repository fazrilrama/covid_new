<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class StockEntryDetail extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stock_entry_details';
    protected $fillable = ['item_id', 'warehouse_id', 'storage_id', 'uom_id','ref_code', 'control_date', 'volume', 'weight', 'begining_qty', 'qty', 'ending_qty', 'status'];


    public function header()
    {
        return $this->belongsTo('App\StockEntry','stock_entry_id','id');
    }

    public function item()
    {
        return $this->belongsTo('App\Item','item_id','id');
    }

    public function uom()
    {
        return $this->belongsTo('App\Uom','uom_id','id');
    }    

    public function storage()
    {
        return $this->belongsTo('App\Storage','storage_id','id');
    }
}
