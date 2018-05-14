<?php

namespace App\Http\Middleware;

use Closure;

class InstallerMiddleware
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
        include FILE_ADMIN_PANEL_SETTINGS;

        if ($globalSettings["installed"] == 0) {
            return redirect("/installer");
        }

        return $next($request);
    }
}
