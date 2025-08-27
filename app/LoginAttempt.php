<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoginAttempt extends Model
{
    protected $table = 'login_attempts';
    protected $guarded = [];
    protected $casts = [
        'is_active' => 'boolean',
    ];
}
