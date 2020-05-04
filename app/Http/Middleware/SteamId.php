<?php

namespace App\Http\Middleware;

use Closure;

class SteamId
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
        if (auth()->user()->steamid == null) {
            return redirect('/steam');
        }

        return $next($request);
    }
}
