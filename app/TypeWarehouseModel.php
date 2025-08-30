<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TypeWarehouseModel extends Model
{
    protected $table = 'warehouse_type';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'color'];
}
