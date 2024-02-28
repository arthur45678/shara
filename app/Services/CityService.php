<?php

namespace App\Services;

use App\Contracts\CityInterface;
use App\City;

class CityService implements CityInterface
{
	/**
	 * Object of City class.
	 *
	 * @var $city 
	 */
	private $city;

	/**
	 * Create a new instance of CategoryService class.
	 *
	 * @return void
	 */

	public function __construct()
	{
		$this->city = new City(); 
	}

	public function getAllCities()
	{
		return $this->city->all();
	}

	public function getAllCitiesPaginate()
	{
		return $this->city->paginate(50);
	}

	public function createCity($data)
	{
		return $this->city->create($data);
	}

	public function getCityById($id)
	{
		return $this->city->where('id', $id)->first();
	}

	public function editCity($object, $data)
	{
		return $object->update($data);
	}

	public function deleteCity($object)
	{
		return $object->delete();
	}

	public function getCityByName($name)
	{
		return $this->city->where('name', $name)->first();
	}

	public function searchCityInCountry($name, $country_id)
	{

		return $this->city->where('name', 'LIKE', '%'.$name.'%')->where('country_id', $country_id)->get();
	}

	public function searchCity($name)
	{
		return $this->city->where('name', 'LIKE', '%'.$name.'%')->get();
	}

	public function autocompleteCities($country_id, $value)
	{
		return $this->city->where('country_id', $country_id)->where('name', 'LIKE', '%'.$value.'%')->get();
	}

	public function getCitiesOrder($country_id)
	{
		return $this->city->where('country_id', $country_id)->withCount('companies')->limit(8)->get();
	}


}