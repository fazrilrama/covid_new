<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WarehouseOfficer extends Model
{
    protected static $logAttributes = ['user_id','warehouse_id'];

    protected $fillable = ['user_id','warehouse_id'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'warehouse_officer';

    public function user()
    {
        return $this->belongsTo('App\User','user_id','id');
    }

    public function warehouse()
    {
        return $this->belongsTo('App\Warehouse','warehouse_id','id');
    }
}
