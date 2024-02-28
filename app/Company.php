<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Company extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'name', 'parent_id', 'url', 'description', 'short_description', 'logo', 'facebook_url', 'linkedin_url', 'twitter_url', 'crunchbase_url', 'ios_url', 'android_url', 'country_id', 'city_id', 'sector_id', 'category_id', 'looking_for', 'requirement', 'compensation', 'why_us', 'job_applying', 'url_to_redirect', 'city_name', 'city_longtitude', 'city_latitude', 'city_population', 'sub_type', 'restrict', 'country_parent', 'region', 'meta_description', 'meta_keywords'
    ];

    public function country()
    {
    	return $this->belongsTo('App\Country');
    }

    public function city()
    {
    	return $this->belongsTo('App\City');
    }

    public function sector()
    {
    	return $this->belongsTo('App\Sector');
    }

    public function category()
    {
    	return $this->belongsTo('App\Category');
    }

    public function subsidiaries()
    {
    	return $this->hasMany('App\Company', 'parent_id');
    }

    public function generic()
    {
    	return $this->belongsTo('App\Company', 'parent_id');
    }

    public function countryParent()
    {
        return $this->belongsTo('App\Company', 'country_parent');
    }

    public function citySubsidiaries()
    {
        return $this->hasMany('App\Company', 'country_parent');
    }

    public function jobs()
    {
        return $this->hasMany('App\Job');
    }

    public function categoryTranslation()
    {
        return $this->hasOne('App\CategoryTranslation', 'category_id', 'category_id');
    }

    public function admins()
    {
        return $this->belongsToMany('App\User', 'admin_company', 'company_id', 'user_id');
    }


    public function getCompaniesByLocations($latitude, $longtitude)
    {
        return $this->select('*', DB::raw("( 6371 * acos( cos( radians(".$latitude.") ) * cos( radians( city_latitude ) ) * cos( radians( city_longtitude ) - radians(".$longtitude.") ) + sin( radians(".$latitude.") ) * sin( radians( city_latitude ) ) ) ) AS distance"));
    }

    public function sectorTranslation() 
    {
        return $this->hasOne('App\SectorTranslation', 'sector_id', 'sector_id');
    }

    /**
     * company owns many city subsidiaries
     *
     * @return collection
     */
    public function cities()
    {
        return $this->hasMany('App\CompanyCity', 'company_id');
    }

}
