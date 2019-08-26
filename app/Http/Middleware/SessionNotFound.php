<?php

namespace App\Http\Middleware;

use Closure;

class SessionNotFound
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
        $_app_path = $request->segment(1);
        $_action = $request->segment(2);
        // var_dump($_action);exit;
        if (empty(session($_app_path . ".login")) && !empty($_action)) {
            return redirect($_app_path);
        }

        return $next($request);
    }
}
