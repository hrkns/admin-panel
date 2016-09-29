<?php

namespace App\Http\Middleware;

use Closure;

class LockScreenMiddleware
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
        if($request->session()->has("lock_screen") && $request->session()->get("lock_screen") == "1"){
            return redirect("/lock-screen");
        }

        return $next($request);
    }
}
