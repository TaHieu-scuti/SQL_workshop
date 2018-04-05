<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class RedisAuth
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
        if (Auth::check() || Auth::guard('redisGuard')->check()) {
            return $next($request);
        }
        return redirect('/login');
    }
}
