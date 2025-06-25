<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Session;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        
        switch ($guard) {
            case 'admin':
                if (Auth::guard($guard)->check()) {
                    Session::flash('info', 'You are already logged in'); 
                    return redirect()->route('admin.dashboard');
                }
                break;
            
            default:
                if (Auth::guard($guard)->check()) {
                    Session::flash('info', 'You are already logged in'); 
                    return redirect()->route('home');
                }
                break;
        }

        return $next($request);
    }
}
