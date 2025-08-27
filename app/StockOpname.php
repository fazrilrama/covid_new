<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockOpname extends Model
{
    protected $table = 'stock_opnames';
    protected $guarded = [];

    public function details()
    {
        return $this->hasMany('App\StockOpnameDetail','stock_opname_id','id');
    }

    public function warehouse()
    {
        return $this->belongsTo('App\Warehouse','warehouse_id','id');
    }
}
