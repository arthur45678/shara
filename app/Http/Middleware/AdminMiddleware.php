<?php

namespace App\Http\Middleware;

use Closure;
use Sentinel;
use View;

class AdminMiddleware
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

        if(Sentinel::getUser() && Sentinel::getUser()->activation =='activated')
            return $next($request);
        else
            Sentinel::logout(Sentinel::getUser());
            return redirect()->action('Admin\AdminController@getLogin');
    }
}
