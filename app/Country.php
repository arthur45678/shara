<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Country extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'abbreviation', 'language', 'currency', 'metric', 'restrict', 'latitude', 'lontitude', 'capital'
    ];

    public function cities()
    {
    	return $this->hasMany('App\City', 'country_id');
    }

    public function companies()
    {
        return $this->hasMany('App\Company', 'country_id');
    }

    public function jobs()
    {
        return $this->hasMany('App\Job', 'country_id');
    }

    public function categoryJobs()
    {
        return $this->hasMany('App\Job', 'country_id')->has('category')->with('category');
    }

    public function companyCategories()
    {
        return $this->hasMany('App\Company', 'country_id')->where(function($query) {
            $query->whereNull('restrict')->orwhere('restrict', '!=', 'true');
        })->where('type', 'subsidiary')->where('sub_type', 'country_subsidiary')->whereHas('jobs', function($query) {
                $query->whereNull('jobs.restrict')
                      ->orWhere('jobs.restrict', '!=', 'true');
            })->has('category')->with('category');

    }

    public function topCategories($count)
    {
        return $this->companyCategories()
            ->selectRaw('category_id, count(*) as count')->orderBy('count', 'desc')->take($count)->groupBy('category_id');
    }

    public function companySectors()
    {
        return $this->hasMany('App\Company', 'country_id')->where(function($query) {
            $query->whereNull('restrict')->orwhere('restrict', '!=', 'true');
        })->where('type', 'subsidiary')->where('sub_type', 'country_subsidiary')->whereHas('jobs', function($query) {
                $query->whereNull('jobs.restrict')
                      ->orWhere('jobs.restrict', '!=', 'true');
            })->has('sector')->with('sector');
    }

    public function topSectors()
    {
        return $this->companySectors()
         ->selectRaw('sector_id, count(*) as count')->orderBy('count', 'desc')->take(9)->groupBy('sector_id');
    }


    public function companyCities()
    {
        return $this->hasMany('App\CompanyCity', 'country_id')->whereHas('company', function($query) {
            $query->where('type', 'subsidiary')->where('sub_type', 'country_subsidiary')->whereHas('jobs', function($query) {
                $query->whereNull('jobs.restrict')
                      ->orWhere('jobs.restrict', '!=', 'true');
            });
            $query->where(function ($q) {
                $q->whereNull('restrict')->orwhere('restrict', '!=', 'true');
            });
        });
    }

    public function topCities()
    {
        return $this->companyCities()
         ->selectRaw('city, count(*) as count, latitude, longitude')->orderBy('count', 'desc')->take(9)->groupBy('city');
    }
}