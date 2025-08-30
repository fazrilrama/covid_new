<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SuhuModel extends Model
{
    protected $table = "suhu";
    protected $primaryKey = 'id';
    protected $fillable = [
        'warehouse_id',
        'tindakan_id',
        'time',
        'petugas',
        'anter_room',
        'chamber_2',
        'chamber_3',
        'kulkas',
        'catatan',
    ];
}
