<?php

namespace App\Http\Middleware;

use Closure;

class PreventAccessingWhenChoosingYssEngine
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
        if (session('engine') === 'yss') {
            return Response(view('errors.error404'));
        }
        return $next($request);
    }
}
