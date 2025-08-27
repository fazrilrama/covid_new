<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class AdvanceNoticeActivity extends Model
{
    use LogsActivity;

    protected static $logAttributes = ['name'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stock_advance_notice_activities';

    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }

    public function advance_notices()
    {
        return $this->hasMany('App\AdvanceNotice','advance_notice_activity_id','id');
    }
}
