<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class CompanyType extends Model
{
    use LogsActivity;

    protected static $logAttributes = ['name'];
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'company_types';

    public function companies()
    {
        return $this->hasMany('App\Company','company_type_id','id');
    }
}
