<?php

namespace App\Http\Middleware;
use App\Models\UserSession;

use Closure;

class SessionVerification
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
        if( !$request->session()->has("idsession") ||
            count(UserSession::where("id", "=", $request->session()->get("idsession"))->get()) == 0){
            //session already closed
            return \Response::json([
                "session" => "finished"
            ], 401);
        }

        if( $request->session()->get("use_session_duration") == "1" &&
            difftime($request->session()->get("started_at"), sqldate()) > intval($request->session()->get("session_duration_limit"))){
            //session timeout
            $request->session()->forget(PROGRESSIVE_REQUEST_TOKENS);
            $request->session()->forget('idsession');
            $request->session()->forget('iduser');
            $request->session()->forget('datauser');
            return \Response::json([
                "session" => "finished"
            ], 401);
        }

        if($request->session()->has("lock_screen") && $request->session()->get("lock_screen") == "1"){
            //locked screen
            return \Response::json([
                "session" => "locked"
            ], 401);
        }

        return $next($request);
    }
}
