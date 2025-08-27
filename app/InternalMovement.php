<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class InternalMovement extends Model
{
    protected $table = 'internal_movements';

    public function getDocIntCode()
    {
        return 'IMT';
    }

    public function details()
    {
        return $this->hasMany('App\InternalMovementDetail','internal_movement_id','id');
    }

    public function detailmovement()
    {
        return $this->hasMany('App\InternalMovementDetail','internal_movement_id','id')->select([DB::raw('sum(movement_qty) as movement_qty,origin_storage_id, dest_storage_id'), 'item_id', 'movement_uom_id', 'id',
        'ref_code'])->groupBy('item_id', 'origin_storage_id', 'ref_code');
    }

    public function warehouse()
    {
        return $this->belongsTo('App\Warehouse', 'warehouse_id', 'id');
    }
}
