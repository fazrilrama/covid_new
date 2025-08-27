<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class PartyType extends Model
{
    use LogsActivity;

    protected static $logAttributes = ['name'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'party_types';

    public function parties()
    {
        return $this->belongsToMany('App\Party','parties_party_types','party_type_id','party_id');
    }

    public function parties_party_types()
    {
        return $this->hasMany('App\PartiesPartyTypes','party_type_id','id');
    }

    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }
}
