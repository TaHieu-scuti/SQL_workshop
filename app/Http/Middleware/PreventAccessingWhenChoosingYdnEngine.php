<?php

namespace App\Http\Middleware;

use Closure;

class PreventAccessingWhenChoosingYdnEngine
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
        if (session('engine') === 'ydn') {
            return redirect('/error');
        }
        return $next($request);
    }
}
