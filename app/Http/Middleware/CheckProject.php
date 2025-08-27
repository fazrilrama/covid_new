<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\User;
class CheckProject
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //return $request->user();
        /* if ($user =$request->user()) {
            if ( Auth::user()->hasRole('Superadmin') ) {
                return redirect('/companies');
            }
            if ( Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Admin-BGR') || Auth::user()->hasRole('Admin-Client') ) {
                if (empty(session()->get('current_project'))) {
                    return redirect('empty-project');
                }
                return redirect('/projects');
            }
            if (empty(session()->get('current_project'))) {
                return redirect('empty-project');
            }
        }   */

        return $next($request);
    }
}
