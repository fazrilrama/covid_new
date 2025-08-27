<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockOpnameDetail extends Model
{
    protected $table = 'stock_opname_details';
    protected $fillable = ['item_id', 'uom_id', 'storage_id', 'on_hand', 'wina_stock', 'actual_stock', 'created_at', 'updated_at', 'deleted_at', 'po_sto', 'so', 'stock_adm', 'stock_card_physic', 'project_id', 'stock_opname_id'];

    protected $guarded = [];

    public function item()
    {
        return $this->belongsTo('App\Item','item_id','id');
    }

    public function uom()
    {
        return $this->belongsTo('App\Uom','uom_id','id');
    }    

    public function project()
    {
        return $this->belongsTo('App\Project');
    }

    public function header()
    {
        return $this->belongsTo('App\StockOpname','stock_opname_id','id');
    }

    public function storage()
    {
        return $this->belongsTo('App\Storage','storage_id','id');
    }
}
