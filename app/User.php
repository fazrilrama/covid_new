<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laratrust\Traits\LaratrustUserTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use App\AdvanceNotice;
use App\StockEntry;


class User extends Authenticatable
{
    use HasApiTokens;
    use LaratrustUserTrait;
    use Notifiable;
    use LogsActivity;

    protected static $logAttributes = ['first_name','last_name','email','mobile_number','work_position','user_id','branch_id'];

    protected $fillable = [
        'first_name', 'email', 'user_id', 'email', 'password',
    ];

    protected $hidden = [
        'remember_token',
    ];

    public function findForPassport($user_id) {
        return $this->where('user_id', $user_id)->first();
    }

    public function scopeIsLocked($query){
        $query->where('is_locked', 1);
    }

    public function Warehouse()
    {
        return $this->belongsToMany('App\Warehouse','warehouse_officer','user_id','warehouse_id');
    }

    public function warehouse_officer()
    {
        return $this->hasMany('App\WarehouseOfficer','user_id','id');
    }

    public function branch()
    {
        return $this->belongsTo('App\Party','branch_id','id');
    }

    public function roles()
    {
        return $this->belongsToMany('App\Role', 'role_user', 'user_id', 'role_id');
    }

    public function getFirstNameAttribute($value)
    {
        return ucfirst($value);
    }

    public function getLastNameAttribute($value)
    {
        return ucfirst($value);
    }

    public function projects()
    {
        return $this->belongsToMany('App\Project','assigned_to','user_id','project_id');
    }

    public function getFullNameAttribute($value)
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function whmNotification()
    {
        // $collections = AdvanceNotice::where('status', 'Completed')->orderBy('id', 'desc')->get();

        // $collections = $collections->filter(function ($item) {
        //     if($item->type == 'inbound'){
        //         return $item->consignee_id == $this->branch->id;
        //     }
        //     else{
        //         return $item->shipper_id == $this->branch->id;
        //     }
        // });

        // $collections = $collections->filter(function ($item) {
        //     return $item->employee_name == null || $item->employee_name == '';
        // });
        // // $collections = AdvanceNotice::where('status', 'Completed')
        // //     ->whereNull('user_id2')
        // //     ->whereIn('type', ['inbound', 'outbound'])
        // //     ->orderBy('id', 'desc'); 

        // // $collections = $collections->filter(function ($item) {
        // //     if($item->type == 'inbound'){
        // //         return $item->consignee_id == $this->branch->id;
        // //     }
        // //     else{
        // //         return $item->shipper_id == $this->branch->id;
        // //     }
        // // });

        // $collections = $collections->filter(function ($item) {
        //     return $item->user_id2 == null;
        // });
        // // $collections = $collections->get(); 
        // // return $collections;

        // return $collections;
        $collections = AdvanceNotice::where('status', 'Completed')->orderBy('id', 'desc')->get();

        $collections = $collections->filter(function ($item) {
            if($item->type == 'inbound'){
                return $item->consignee_id == $this->branch->id;
            }
            else{
                return $item->shipper_id == $this->branch->id;
            }
        });

        $collections = $collections->filter(function ($item) {
            return $item->employee_name == null || $item->employee_name == '';
        });

        return $collections;

        //return AdvanceNotice::whereNull('employee_name')->where('status', 'Completed')->where('consignee_id', $this->branch->id)->orWhere('shipper_id', $this->branch->id)->orderBy('id', 'desc')->get();
        
    }

    public function completeNotification()
    {
        $collections = StockEntry::where('status', 'Completed')->where('type', 'outbound')->whereIn('warehouse_id', ['892', '1385'])->where('is_seen', 0)->orderBy('id', 'desc')->limit(20)->get();

        // $collections = $collections->filter(function ($item) {
        //     if($item->type == 'inbound'){
        //         return $item->consignee_id == $this->branch->id;
        //     }
        //     else{
        //         return $item->shipper_id == $this->branch->id;
        //     }
        // });

        // $collections = $collections->filter(function ($item) {
        //     return $item->employee_name == null || $item->employee_name == '';
        // });

        return $collections;
    }

    public function completeTransporterNotification()
    {
        return StockEntry::where('is_seen', 0)->where('type', 'outbound')->whereIn('warehouse_id', ['892', '1385'])->orderBy('id', 'desc')->limit(20)->get();
    }

    public function whsNotification()
    {
        return AdvanceNotice::where('is_seen', 0)->where('employee_name',$this->first_name.' '.$this->last_name)->orderBy('id', 'desc')->get();
    }

    public function whcNotification()
    {
        return AdvanceNotice::where('is_accepted', 2)->where('user_id', $this->id)->orderBy('id', 'desc')->get();
    }

    // public function spvNotification()
    // {
    //     return 'SPV' . session()->get('current_project')->id;
    // }
}
