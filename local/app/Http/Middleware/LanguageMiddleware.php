<?php

namespace App\Http\Middleware;

use Closure;

class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
        if($request->has("data.lng")){
            define("__LNG__", $request->input("data.lng"));
        }else if($request->session()->has("lng")){
            define("__LNG__", $request->session()->get("lng"));
        }else{
            define("__LNG__", DEFAULT_LANGUAGE);
        }

        if(!$request->session()->has("lng")){
            $request->session()->put("lng", DEFAULT_LANGUAGE);
        }

        define("AMOUNT_ITEMS_PER_REQUEST", $request->session()->get('amount_items_per_request'));

        return $next($request);
    }
}
