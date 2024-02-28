<?php

namespace App\Http\Middleware;

use App\Contracts\CountryInterface;
use Closure;

class LanguageMiddleware
{

    /**
     * Object of CountryInterface class
     *
     * @var countryRepo
     */
    private $countryRepo;

    public function __construct(CountryInterface $countryRepo)
    {
        $this->countryRepo = $countryRepo;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->lang) {
            $country = $this->countryRepo->getCountryByLocale($request->lang);
            if($country) {
                \App::setLocale($country->language);
            }else {
                \App::setLocale($request->lang);
            }
        }else {
            if(!\App::getLocale()) {
                \App::setLocale('en');
            }            
        }

        return $next($request);
    }
}
