<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdvanceNoticeDetail extends Model
{
    use LogsActivity;

    protected $table = 'stock_advance_notice_details';

    protected static $logAttributes = ['qty','ref_code'];
    protected $fillable = ['item_id', 'uom_id', 'qty', 'ref_code', 'weight', 'volume'];


    public function header()
    {
        return $this->belongsTo('App\AdvanceNotice','stock_advance_notice_id','id');
    }

    public function item()
    {
        return $this->belongsTo('App\Item','item_id','id');
    }

    public function uom()
    {
        return $this->belongsTo('App\Uom','uom_id','id');
    }
}
