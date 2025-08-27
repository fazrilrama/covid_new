<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LastLogger extends Model
{
    protected $fillable = [
        'user_id',
        'responses',
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
