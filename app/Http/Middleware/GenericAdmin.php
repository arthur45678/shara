<?php

namespace App\Http\Middleware;

use Closure;
use Sentinel;
use View;

class GenericAdmin
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
        View::share([ 'currentUser' => Sentinel::getUser() ]);
        if(Sentinel::getUser() && Sentinel::getUser()->admin_type =='generic')
            return $next($request);
        else
            // return redirect()->action('Admin\AdminController@getLogin');
            return redirect()->back()->with('error', 'You are not allowed.');
    }
}
