<?php

namespace App\Http\Middleware;

use Closure;

class FrontEndUserMiddleWare
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
        if(!auth('frontenduser')->check()){
            if($request->expectsJson()){
                return response()->json([
                    'status'=>false,
                    'error'=>"Session Timeout, Please re-login to continue"
                ]);
            }else {
                return redirect()->route('index');
            }
        }
        return $next($request);
    }
}
