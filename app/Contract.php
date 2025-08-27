<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Contract extends Model
{
    use LogsActivity;

    protected static $logAttributes = ['name'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contracts';

    public function project()
    {
        return $this->belongsTo('App\Project','project_id','id');
    }

    public function company()
    {
        return $this->belongsTo('App\Company','company_id','id');
    }

    public function warehouses()
    {
        return $this->belongsToMany('App\Warehouse','contract_warehouse','contract_id','warehouse_id')->withPivot('rented_space');
    }

    public function spks()
    {
        return $this->hasMany('App\Spk');
    }
}
