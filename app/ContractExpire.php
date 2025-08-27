<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContractExpire extends Model
{
    protected $table = 'contract_expires';

    public function party()
    {
        return $this->belongsTo('App\Party');
    }

    public function contract()
    {
        return $this->belongsTo('App\Contract');
    }
}
