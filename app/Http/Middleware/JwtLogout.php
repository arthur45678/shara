<?php

namespace App\Http\Middleware;

use Closure;



class JwtLogout
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
        try{

            $user = \JWTAuth::parseToken()->authenticate();
            if($user->restrict == 'true'){
                $response = [
                 'error_text' => 'The user is not logged in',
                 'error_status' => -1000
              ];
              return response()->json($response);
            }

        } catch(\Exception $e){
          
          $response = [
             'error_text' => 'The user is not logged in',
             'error_status' => -1000
          ];
            return response()->json($response);
        }
       
        return $next($request);
    }
}
