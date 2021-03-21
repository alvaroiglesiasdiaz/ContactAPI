<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class CheckApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
       // $apiToken = $request->request->get('api_token');
        $apiToken = $request->bearerToken();
        $user = User::where('api_token',$apiToken)->first();
    if($user){
       

       if($user->api_token != null || $user->api_token != "" ){
         print("OK ");

       // return response("Token exists, can loggin",403);
       }
    }else{
        
        print("FAIL");
        return response("Invalid token",401);
    
    
    }
    
        return $next($request);
    }
}
