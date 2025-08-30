<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatusSuhuModel extends Model
{
    protected $table = "status_suhu";
    protected $primaryKey = 'id';
    protected $fillable = ['name'];
}
