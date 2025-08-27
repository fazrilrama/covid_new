<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\User;
use App\LastLogger;
use App\LoginAttempt;
use DataTables;
use Yajra\DataTables\Html\Builder;
use Carbon;

class UserLockedController extends Controller
{
    public function index(){
        $collections = User::isLocked()->orWhere('is_logged_in',1)->get();
        return view('user-locked.index', compact('collections'));
    }

    public function unlock($user_id){
        $user = User::where('user_id', $user_id)->first();
        if (empty($user))
            return redirect('user_locked')->with('error', 'User tidak ditemukan');
        
        DB::beginTransaction();
        try {
            User::where('user_id', $user_id)->update([
                'is_locked' => 0,
                'has_unlock_requested' => 0,
            ]);
            LoginAttempt::where('user_id', $user_id)->delete();
        } catch (\Exception $e) {
            return redirect('user_locked')->with('error', $e->getMessage());
        }
        DB::commit();
        return redirect('user_locked')->with('success', 'User berhasil dibuka');
    }

    public function logged_in(Request $request, Builder $htmlBuilder)
    {
        $collections = LastLogger::orderBy('created_at', 'desc')
                    ->get(); 

        $total_user = User::join('last_loggers as last','last.user_id','=','users.id')
                        //->where('last_loggers.created_at','>', Carbon::now()->subWeek())
                        //->orderBy('created_at', 'desc')
                        ->groupBy('users.id')
                        ->count();

        return view('user-locked.logged_in', compact('collections','total_user'));
    }

    public function unlock_logged_in($user_id){
        $user = User::where('user_id', $user_id)->first();
        if (empty($user))
            return redirect('user_logged_in')->with('error', 'User tidak ditemukan');
        
        DB::beginTransaction();
        try {
            User::where('user_id', $user_id)->update([
                'is_logged_in' => 0,
            ]);
        } catch (\Exception $e) {
            return redirect('user_logged_in')->with('error', $e->getMessage());
        }
        DB::commit();
        return redirect('user_logged_in')->with('success', 'User berhasil logged out');
    }
}
