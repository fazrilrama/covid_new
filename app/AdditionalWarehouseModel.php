<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdditionalWarehouseModel extends Model
{
    protected $table = 'warehouse_additional';
    protected $primaryKey = 'id';
    protected $fillable = ['name'];
}
