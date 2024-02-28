<?php

namespace App\Http\Middleware;

use Closure;

class Cors
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
		// return $next($request)
		//     ->header('Access-Control-Allow-Origin', '*')
		//     ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
		$headers = [
		   // 'Access-Control-Allow-Origin' => '*',
		   'Access-Control-Allow-Methods'=> 'POST, GET, OPTIONS, PUT, DELETE',
		   'Access-Control-Allow-Headers'=> '*'
	   ];
	   if($request->getMethod() == "OPTIONS") {
		   // The client-side application can set only headers allowed in Access-Control-Allow-Headers
		   return Response::make('OK', 200, $headers);
	   }

	   $response = $next($request);

	   foreach($headers as $key => $value) {

		   $response->headers->set($key, $value);
	   }

	   return $response;
	}
}
