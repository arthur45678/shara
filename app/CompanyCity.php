<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyCity extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'company_id',
		'city',
		'latitude',
		'longitude',
		'country_id',
		'region'
	];

	/**
	 * city belongs to company
	 * 
	 * @return object
	 */
	public function company()
	{
		return $this->belongsTo('App\Company', 'company_id');
	}

}
