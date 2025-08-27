<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemPutaway extends Model
{
    use SoftDeletes;
    
    protected static $logAttributes = [
        'stock_entry_id', 
        'total_pallet',
        'total_labor',
        'total_forklift',
        'forklift_start_time',
        'forklift_end_time',
        'user_id'
    ];

    protected $fillable = [
        'stock_entry_id', 
        'total_pallet',
        'total_labor',
        'total_forklift',
        'forklift_start_time',
        'forklift_end_time',
        'user_id'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'item_putaways';
}
