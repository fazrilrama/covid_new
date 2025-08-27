<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartiesPartyTypes extends Model
{
    protected static $logAttributes = ['party_id','party_type_id'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'parties_party_types';

    public function party_types()
    {
        return $this->belongsTo('App\PartyType','party_type_id','id');
    }

    public function party()
    {
        return $this->belongsTo('App\Party','party_id','id');
    }
}
