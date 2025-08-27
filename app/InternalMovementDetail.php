<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InternalMovementDetail extends Model
{
    protected $table = 'internal_movement_details';
    protected $fillable = ['warehouse_id','item_id', 'ref_code', 'control_date', 'origin_storage_id', 'origin_beginning_qty', 'origin_uom_id', 'origin_weight', 'origin_volume', 'movement_qty', 'movement_uom_id', 'movement_weight', 'movement_volume', 'dest_storage_id', 'dest_beginning_qty', 'dest_uom_id', 'dest_weight', 'dest_volume', 'created_at', 'updated_at', 'note', 'project_id'];


    public function header()
    {
        return $this->belongsTo('App\InternalMovement','internal_movement_id','id');
    }

    public function item()
    {
        return $this->belongsTo('App\Item','item_id','id');
    }

    public function uom()
    {
        return $this->belongsTo('App\Uom','movement_uom_id','id');
    }

    public function origin_storage()
    {
        return $this->belongsTo('App\Storage','origin_storage_id','id');
    }

    public function dest_storage()
    {
        return $this->belongsTo('App\Storage','dest_storage_id','id');
    }
}
