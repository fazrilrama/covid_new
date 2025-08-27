<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemWeigher extends Model
{
    use SoftDeletes;
    
    protected static $logAttributes = ['stock_transport_id', 'stock_transport_detail_id', 'qty', 'content_weight', 'empty_weight', 'volume', 'uom_id', 'user_id'];

    protected $fillable = ['stock_transport_id', 'stock_transport_detail_id', 'qty', 'content_weight', 'empty_weight', 'volume', 'uom_id', 'user_id'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'item_weighers';
}
