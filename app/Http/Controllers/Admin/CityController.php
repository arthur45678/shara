<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\CityCreateRequest;
use App\Http\Requests\CityEditRequest;
use App\Http\Controllers\Controller;
use App\Contracts\CityInterface;
use App\Contracts\CountryInterface;
use Illuminate\Validation\Rule;
use Sentinel;
use Validator;
use File;

class CityController extends Controller
{
    /**
     * Object of CityInterface class
     *
     * @var cityRepo
     */
    private $cityRepo;

    /**
     * Object of CountryInterface class
     *
     * @var countryRepo
     */
    private $countryRepo;

    /** 
     * Create a new instance of Controller class.
     *
     * @param CityInterface $cityRepo
     * @return void
     */
	public function __construct(CityInterface $cityRepo, CountryInterface $countryRepo)
	{
		$this->cityRepo = $cityRepo;
		$this->countryRepo = $countryRepo;
		$this->middleware("admin");
	}

	/**
	 * get all cities page
	 *
	 * @return view
	 */
	public function getCities()
	{
		$logged_user = Sentinel::getUser();
		if($logged_user->hasAccess('city.view'))
		{
			$cities = $this->cityRepo->getAllCitiesPaginate();
			$countries = $this->countryRepo->getAllCountries();
			$data = ['cities' => $cities,
					 'countries' => $countries];
			return view('admin.cities', $data);
		}else{
			return redirect()->back();
		}
	}

	/**
	 * get add city page
	 *
	 * @return redirect
	 */
	public function getAddCity()
	{
		$logged_user = Sentinel::getUser();
		if($logged_user->hasAccess('city.create'))
		{
			$countries = $this->countryRepo->getAllCountries();
			$data = ['countries' => $countries];
			return view('admin.add_city', $data);
		}else{
			return redirect()->back();
		}
	}

	/**
	 * add city
	 *
	 * @param CityCreateRequest $request
	 * @return redirect
	 */
	public function postAddCity(CityCreateRequest $request)
	{
		$name = $request->name;
		$latitude = $request->latitude;
		$longtitude = $request->longtitude;
		$population = $request->population;
		$country_id = $request->country; 
		$data = [
			'name' => $name,			
			'longtitude' => $longtitude,
			'latitude' => $latitude,
			'population' => $population,
			'country_id' => $country_id
		];
		$city = $this->cityRepo->createCity($data);
		return redirect()->action('Admin\CityController@getCities');
	}

	/**
	 * get edit city page
	 *
	 * @param int $city_id
	 * @return view
	 */
	public function getEditCity($city_id)
	{
		$logged_user = Sentinel::getUser();
		if($logged_user->hasAccess('city.update'))
		{
			$city = $this->cityRepo->getCityById($city_id);
			$countries = $this->countryRepo->getAllCountries();
			$data = ['city' => $city, 'countries' => $countries];
			return view('admin.edit_city', $data);
		}else{
			return redirect()->back();
		}
	}

	/**
	 * edit city
	 *
	 * @param CityEditRequest $request
	 * @return redirect
	 */
	public function postEditCity(CityEditRequest $request)
	{
		$name = $request->name;
		$latitude = $request->latitude;
		$longtitude = $request->longtitude;
		$population = $request->population;
		$country_id = $request->country;
		$city_id = $request->city_id;
		$city = $this->cityRepo->getCityById($city_id);
		$data = [
			'name' => $name,			
			'longtitude' => $longtitude,
			'latitude' => $latitude,
			'population' => $population,
			'country_id' => $country_id
		];
		$validator = Validator::make($data, [
                'name' => [
                    'required',
                    Rule::unique('cities')->ignore($city_id),
                ],
            ]);
		if ($validator->fails()) {
			return redirect()->back()->with('same_name', 'The name has already been taken.');
		}

		$edited_city = $this->cityRepo->EditCity($city, $data);
		return redirect()->action('Admin\CityController@getCities');
	}

	/**
	 * get show city page
	 *
	 * @param int $city_id
	 * @return view
	 */
	public function getShowCity($city_id)
	{
		$logged_user = Sentinel::getUser();
		if($logged_user->hasAccess('city.view'))
		{
			$city = $this->cityRepo->getCityById($city_id);
			$data = ['city' => $city];
			return view('admin.show_city', $data);
		}else{
			return redirect()->back();
		}
	}

	/**
	 * delete city
	 *
	 * @param int $city_id
	 * @return redirect
	 */
	public function getDeleteCity($city_id)
	{
		$logged_user = Sentinel::getUser();
		if($logged_user->hasAccess('city.view'))
		{
			$city = $this->cityRepo->getCityById($city_id);
			$this->cityRepo->deleteCity($city);
			return redirect()->action('Admin\CityController@getCities');
		}else{
			return redirect()->back();
		}
	}

	/**
	 * get add cities with csv file page
	 *
	 * @return view
	 */
	public function getAddCities()
	{
		$logged_user = Sentinel::getUser();
		if($logged_user->hasAccess('city.create'))
		{
			return view('admin.add_cities');
		}else{
			return redirect()->back();
		}
	}

	/**
	 * add multiple cities with csv file
	 *
	 * @param Request $request
	 * @return redirect
	 */
	public function postAddCities(Request $request)
	{
		$file = $request->csv_file;
        if($file)
        {
            $destinationPath = public_path().'/csv';
            $fileName = $file->getClientOriginalName();
            $file->move($destinationPath, $fileName);
            $csv = array_map('str_getcsv', file($destinationPath.'/'.$fileName));
            $csvArray = [];
            foreach($csv as $fieldKey => $csvRow)
            {
                $csvField = [];
                $csvArray[$fieldKey] = $csvRow;
            }
            $cityInfoArr = [];
            // foreach($csvArray as $cityInfo)
            // {
            // 	$country = $this->countryRepo->getCountryByName($cityInfo[0]);
            // 	if($country == null)
            // 	{
            // 		return redirect()->back()->with('csv_file', 'Wrong csv file');
            // 	}
            // 	$city_country_id = $country->id;
            // 	$city_name = $cityInfo[1];
            // 	$city_latitude = $cityInfo[2];
            // 	$city_longtitude = $cityInfo[3];
            // 	$city_population = $cityInfo[4];
            // 	$city_data = [
            // 		'country_id' => $city_country_id,
            // 		'name' => $city_name,
            // 		'latitude' => $city_latitude,
            // 		'longtitude' => $city_longtitude,
            // 		'population' => $city_population

            // 	];
            // 	$validator = Validator::make($city_data, [
	           //      'name' => [
	           //          'required',
	           //          Rule::unique('cities'),
	           //      ],
	           //      'country_id' => ['required'],
	           //      'latitude' => ['required', 'numeric'],
	           //      'longtitude' => ['required', 'numeric'],
	           //      'population' => ['required', 'numeric']
	           //  ]);
            // 	if($validator->fails())
            // 	{
            // 		return redirect()->back()->with('csv_file', 'Wrong csv file');
            // 	}
            // }
            foreach($csvArray as $cityInfo)
            {
            	$country = $this->countryRepo->getCountryByName($cityInfo[0]);
            	if($country == null)
            	{
            		return redirect()->back()->with('csv_file', "There is a country in your csv file, that doesn't exist.");
            	}
            	$city_country_id = $country->id;
            	$city_name = $cityInfo[1];
            	$city_latitude = $cityInfo[2];
            	$city_longtitude = $cityInfo[3];
            	$city_population = $cityInfo[4];
            	$city_data = [
            		'country_id' => $city_country_id,
            		'name' => $city_name,
            		'latitude' => $city_latitude,
            		'longtitude' => $city_longtitude,
            		'population' => $city_population

            	];
            	
            	$validator = Validator::make($city_data, [
	                'name' => [
	                    'required',
	                    Rule::unique('cities'),
	                ],
	                'country_id' => ['required'],
	                'latitude' => ['required', 'numeric'],
	                'longtitude' => ['required', 'numeric'],
	                'population' => ['required', 'numeric']
	            ]);
            	if($validator->fails())
            	{
            		$failedRules = $validator->failed();
    				if(isset($failedRules['name']['Unique']) && count($failedRules) == 1)
    				{
    					continue;
    				}
            		return redirect()->back()->with('csv_file', 'Wrong csv file');
            	}
            	$this->cityRepo->createCity($city_data);
            }
            return redirect()->action('Admin\CityController@getCities');
            
        }else{
        	return redirect()->back()->with('csv_file', 'Choose a file.');
        }
	}

	public function getCountryCities(Request $request, $countryId)
	{
		$value = $request->value;
		// $cities = $country_object->cities;
		$cities = $this->cityRepo->autocompleteCities($countryId, $value);
		$cities_names = [];
		if($cities)
		{			
			foreach($cities as $city)
			{
				$cities_names[] = $city->name;
			}
		}		
		$data = ['cities' => $cities_names];
		return response()->json($data);
	}

	public function postSearchCity(Request $request)
	{
		$name = $request->city_search;
		$country_name = $request->country;
		if($country_name)
		{
			$country_object = $this->countryRepo->getCountryByName($country_name);
			$country_id = $country_object->id;
		}
		
		if(isset($country_id))
		{
			$results = $this->cityRepo->searchCityInCountry($name, $country_id);
		}else{
			$results = $this->cityRepo->searchCity($name);
		}
		
		$countries = $this->countryRepo->getAllCountries();
		$data = ['search_results' => $results,
				 'countries' => $countries];
		return view('admin.search_results_city', $data);
	}

}
