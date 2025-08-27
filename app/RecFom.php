<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecFom extends Model
{
    protected $table = 'rec_foms';
    protected $fillable = [
        'stock_transport_id',
        'foms_id',
        'response'
    ];
}
