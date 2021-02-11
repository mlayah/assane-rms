<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
               // return redirect(RouteServiceProvider::HOME);
                //return redirect('login');
                if (Auth::user()->hasRole('admin')||Auth::user()->hasRole('agent')||Auth::user()->hasRole('staff')){
                    return redirect(RouteServiceProvider::ADMINHOME);
                }
                if (Auth::user()->hasRole('tenant')||Auth::user()->hasRole('landlord')||Auth::user()->hasRole('user')){
                    return redirect(RouteServiceProvider::USERHOME);
                }
            }
        }

        return $next($request);
    }
}
