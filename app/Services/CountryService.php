<?php

namespace App\Services;

use App\Contracts\CountryInterface;
use App\Country;

class CountryService implements CountryInterface
{

	/**
	 * Object of Country class.
	 *
	 * @var $country 
	 */
	private $country;

	/**
	 * Create a new instance of CountryService class.
	 *
	 * @return void
	 */

	public function __construct()
	{
		$this->country = new Country(); 
	}

	public function getAllCountries()
	{
		return $this->country->orderBy('name', 'asc')->get();
	}

	public function getAllCountriesPaginate()
	{
		return $this->country->orderBy('name', 'asc')->paginate(50);
	}

	public function createCountry($data)
	{
		return $this->country->create($data);
	}

	public function getCountryById($id)
	{
		return $this->country->where('id', $id)->first();
	}

	public function editCountry($object, $data)
	{
		return $object->update($data);
	}

	public function deleteCountry($object)
	{
		return $object->delete();
	}

	public function getCountryByName($name)
	{
		return $this->country->where('name', $name)->first();
	}

	public function getCountryByLocale($locale)
	{	
		$locale = $locale === 'unknown' ? "it" : $locale;
		return $this->country->where('abbreviation', $locale)->first();
	}

	public function getCountryByLanguage($code)
	{
		return $this->country->where('language', $code)->first();
	}

}