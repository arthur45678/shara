<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'name', 'about_company', 'why_us', 'benefits', 'requirement', 'schedule', 'country_id', 'city_id', 'sector_id', 'category_id', 'activation', 'job_applying', 'url_to_redirect', 'company_id', 'city_name', 'city_longtitude', 'city_latitude', 'city_population', 'description', 'restrict', 'region', 'compensation','is_sent'
    ];

    public function country()
    {
    	return $this->belongsTo('App\Country');
    }

    public function city()
    {
    	return $this->belongsTo('App\City');
    }

    public function category()
    {
    	return $this->belongsTo('App\Category');
    }

    public function sector()
    {
    	return $this->belongsTo('App\Sector');
    }

    public function company()
    {
        return $this->belongsTo('App\Company');
    }

    public function sectorTranslation()
    {
        return $this->hasMany('App\SectorTranslation', 'sector_id', 'sector_id');
    }

    public function categoryTranslation()
    {
        return $this->hasMany('App\CategoryTranslation', 'category_id', 'category_id');
    }

    public function subscribers()
    {
        return $this->belongsToMany('App\Subscribtion', 'notifications', 'job_id', 'subscribtion_id');
    }

}
