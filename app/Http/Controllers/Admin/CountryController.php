<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Contracts\CountryInterface;
use App\Http\Requests\CreateCountryRequest;
use App\Http\Requests\EditCountryRequest;
use Illuminate\Validation\Rule;
use Sentinel;
use Validator;

class CountryController extends Controller
{
    /**
     * Object of CountryInterface class
     *
     * @var countryRepo
     */
    private $countryRepo;

    /** 
     * Create a new instance of Controller class.
     *
     * @param CountryInterface $CategoryRepo
     * @return void
     */
	public function __construct(CountryInterface $countryRepo)
	{
		$this->countryRepo = $countryRepo;
		$this->middleware("admin");
		$this->middleware("genericAdmin");
	}

	/**
	 * get countries list page
	 * GET /admin/countries
	 *
	 * @return view
	 */
	public function getCountries()
	{
		//get the logged user
		$loggedUser = Sentinel::getUser();

		//check if the user has access to view countries
		if($loggedUser->hasAccess('country.view')){
			//if yes, return countries
			$countries = $this->countryRepo->getAllCountriesPaginate();
			$data = [
					'countries' => $countries
					];
			return view('admin.countries', $data);
		}else{
			//if no, return back
			return redirect()->back();
		}
		
	}

	/**
	 * add country page
	 * GET /admin/add-country
	 *
	 * @return view
	 */
	public function getAddCountry()
	{
		//get the logged user
		$loggedUser = Sentinel::getUser();

		//check if the user has access to add a country
		if($loggedUser->hasAccess('country.create')){
			//if yes, return the page to add a country
			return view('admin.add_country');
		}else{
			//if no, return back
			return redirect()->back();
		}
	}

	/**
	 * add country
	 * POST /admin/add-country
	 *
	 * @param CreateCountryRequest $request
	 * @return redirect
	 */
	public function postAddCountry(CreateCountryRequest $request)
	{
		//get data to create a country
		$name = $request->name;
		$abbreviation = $request->abbreviation;
		$language = $request->language;
		$currency = $request->currency;
		$metric = $request->metric;
		$data = [
			'name' => $name,
			'abbreviation' => $abbreviation,
			'language' => $language,
			'currency' => $currency,
			'metric' => $metric
		];

		//create the country
		$this->countryRepo->createCountry($data);

		//return country management page
		return redirect()->action('Admin\CountryController@getCountries');
	}

	/**
	 * show country page
	 * GET /admin/show-country/{countryId}
	 *
	 * @param int $countryId
	 * @return view
	 */
	public function getShowCountry($countryId)
	{
		//get the logged user
		$loggedUser = Sentinel::getUser();

		//check if the user hass access to view country details
		if($loggedUser->hasAccess('country.view')){
			//if yes, return country details page
			$country = $this->countryRepo->getCountryById($countryId);
			$data = [
					'country' => $country
					];
			return view('admin.show_country', $data);
		}else{
			//if no, return back
			return redirect()->back();
		}
	}

	/**
	 * get update country page
	 * GET /admin/edit-country/{countryId}
	 *
	 * @param int $countryId
	 * @return view
	 */
	public function getEditCountry($countryId, Request $request)
	{
		//get the logged user
		$loggedUser = Sentinel::getUser();

		//check if the user has access to edit the country
		if($loggedUser->hasAccess('country.update')){
			//if yes, return country edit page
			$country = $this->countryRepo->getCountryById($countryId);
			$data = [
					'country' => $country
					];
			return view('admin.edit_country', $data);
		}else{
			//if no, return back
			return redirect()->back();
		}
	}

	/**
	 * edit country
	 * POST /admin/edit-country
	 *
	 * @param EditCountryRequest $request
	 * @return redirect
	 */
	public function postEditCountry(EditCountryRequest $request)
	{
		//get the changed details 
		$name = $request->name;
		$abbreviation = $request->abbreviation;
		$language = $request->language;
		$currency = $request->currency;
		$metric = $request->metric;
		$countryId = $request->country_id;

		//get the country object
		$country = $this->countryRepo->getCountryById($countryId);
		$data = [
				'name' => $name,
				'abbreviation' => $abbreviation,
				'language' => $language,
				'currency' => $currency,
				'metric' => $metric
				];

		//edit the country
		$editedCountry = $this->countryRepo->editCountry($country, $data);

		$url = $request->only('redirects_to');
		//return the country management page
		// return redirect()->action('Admin\CountryController@getCountries');
		return redirect()->to($url['redirects_to']);
	}

	/**
	 * delete country
	 * GET /admin/delete-country/{countryId}
	 *
	 * @param int $countryId
	 * @return redirect
	 */
	public function getDeleteCountry($countryId)
	{
		// //get the logged user
		// $logged_user = Sentinel::getUser();
		// //check if the user has access to delete the country
		// if($logged_user->hasAccess('country.delete')){
		// 	//if yes,
		// 	//get the country object
		// 	$country = $this->countryRepo->getCountryById($countryId);

		// 	//delete the country
		// 	$this->countryRepo->deleteCountry($country);

		// 	//change countri
		// 	$countryJobs = $country->jobs;
		// 	foreach ($countryJobs as $jobKey => $jobValue) {
		// 		$this->jobRepo->ediiJob($jobValue, $countryId);
		// 	}

		// 	//return country management page
		// 	return redirect()->action('Admin\CountryController@getCountries');
		// }else{
		// 	//if no, return back
		// 	return redirect()->back();
		// }
	}
}
