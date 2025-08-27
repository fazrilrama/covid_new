<?php

namespace App\Http\Middleware;

use Closure;

class CheckFirstTimeLogin
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
        if(\Auth::user()->password_change_at == null) {
            return redirect('/forcepassword');
        }
        return $next($request);
    }
}
