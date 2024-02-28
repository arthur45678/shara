<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'localization', 'population', 'latitude', 'longtitude', 'country_id'
    ];

    public function country()
    {
    	return $this->belongsTo('App\Country');
    }

    public function companies()
    {
        return $this->hasMany('App\Company', 'city_id');
    }

    public function jobs()
    {
        return $this->hasMany('App\Job', 'city_id');
    }
}
