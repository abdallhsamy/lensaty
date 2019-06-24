<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use App\UsersSocial;

class checkTokenOfUser
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
        $token = UsersSocial::where('access_token',$request->token)->first();
        if($token){
            return $next($request);
        }
        return response()->json('This User is not authenticated', 404);
    }
}
