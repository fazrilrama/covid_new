<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WarehouseAddMoreModel extends Model
{
    protected $table = 'warehouse_add_more';
    protected $primaryKey = 'id';
    protected $fillable = ['add_id', 'warehouse_id', 'created_at', 'updated_at'];
}
