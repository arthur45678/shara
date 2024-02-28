<?php

namespace App\Services;

use App\Contracts\CompanyInterface;
use Illuminate\Support\Facades\DB; 
use App\Company;
use App\CompanyCity;
use App\Job;
use App\Country;
use Illuminate\Support\Facades\Cache;

class CompanyService implements CompanyInterface
{
	/**
	 * Object of Company class.
	 *
	 * @var $company 
	 */
	private $company;

	/**
	 * Create a new instance of CompanyService class.
	 *
	 * @return void
	 */

	public function __construct()
	{
		$this->company = new Company();
		$this->companyCity = new CompanyCity(); 
		$this->job = new Job();
		$this->country = new Country(); 
	}

	/**
	 * get all companies
	 *
	 * @return array
	 */
	public function getAllCompanies()
	{
		return $this->company->orderBy('name', 'asc')->get();
	}

	/**
	 * get all generic companies
	 *
	 * @return array
	 */
	public function getAllGenerics()
	{ 
		return $this->company->where('type', 'generic')->orderBy('name', 'asc')->get();
	}

	/**
	 * get all subsidiary companies
	 *
	 * @return array
	 */
	public function getSubsidiaries()
	{
		$companies = $this->company->where('type', 'subsidiary')->orderBy('name', 'asc')->get();
		return $companies;
	}

	/**
	 * admin paginated companies
	 *
	 * @param string $sort
	 * @param string $type
	 * @param integer $id
	 * @return array
	 */
	public function adminCompaniesPaginated($sort, $type, $id)
	{
		if($sort == 'name'){
			$companies = $this->company->where('type', 'generic')->orderBy('name', $type)->select('*', 'companies.name as company_name','companies.id as company_id');
		}elseif($sort == 'category')
		{
			$companies = $this->company->where('type', 'generic')->join('category_translations', 'category_translations.category_id', '=', 'companies.category_id')->where('category_translations.locale', 'en')->orderBy('category_translations.name', $type)->select('*', 'companies.name as company_name','companies.id as company_id');
		}elseif($sort == 'industry'){
			$companies = $this->company->where('type', 'generic')->join('sector_translations', 'sector_translations.sector_id', '=', 'companies.sector_id')->where('sector_translations.locale', 'en')->orderBy('sector_translations.name', $type)->select('*', 'companies.name as company_name','companies.id as company_id');
		}elseif($sort == 'date'){
			$companies = $this->company->where('type', 'generic')->select('*', 'companies.name as company_name','companies.id as company_id');
		}

		$companies = $companies->whereHas('admins', function ($query) use ($id)  {
			    $query->where('user_id',  $id);
			})->paginate(50);
		return $companies;
	}

	/**
	 * get all generic companies paginated
	 *
	 * @param string $sort
	 * @param string $type
	 * @return array
	 */
	public function getAllGenericsPaginate($sort, $type) 
	{
		if($sort == 'name'){
			$companies = $this->company->where('type', 'generic')->orderBy('name', $type)->select('*', 'companies.name as company_name','companies.id as company_id')->paginate(50);
		}elseif($sort == 'category')
		{
			$companies = $this->company->where('type', 'generic')->join('category_translations', 'category_translations.category_id', '=', 'companies.category_id')->where('category_translations.locale', 'en')->orderBy('category_translations.name', $type)->select('*', 'companies.name as company_name','companies.id as company_id')->paginate(50);
		}elseif($sort == 'industry'){
			$companies = $this->company->where('type', 'generic')->join('sector_translations', 'sector_translations.sector_id', '=', 'companies.sector_id')->where('sector_translations.locale', 'en')->orderBy('sector_translations.name', $type)->select('*', 'companies.name as company_name','companies.id as company_id')->paginate(50);
		}elseif($sort == 'date'){
			$companies = $this->company->where('type', 'generic')->select('*', 'companies.name as company_name','companies.id as company_id')->paginate(50);
		}
		return $companies; 
	}

	/**
	 * create company
	 *
	 * @param array $data
	 * @return object
	 */
	public function createCompany($data)
	{
		return $this->company->create($data);
	}

	/**
	 * create city
	 *
	 * @param array $data
	 * @return object
	 */
	public function createCity($data)
	{
		$company = $this->companyCity->where('city', $data['city'])->where('latitude', $data['latitude'])->where('longitude', $data['longitude'])->where('company_id', $data['company_id'])->first();
		if($company){
			$status = 'exists';
			return $status;
		}else{
			return $this->companyCity->create($data);
		}
	}

	/**
	 * delete city
	 *
	 * @param integer $id
	 */
	public function deleteCityCompany($id)
	{
		$companyCity = $this->companyCity->where('id', $id)->first();
		if($companyCity){
			return $companyCity->delete();
		}else{
			return ;
		}
	}

	/**
	 * edit company city
	 *
	 * @param integer $id
	 * @param array $data
	 * @return object
	 */
	public function editCompanycity($id, $data)
	{
		return $this->companyCity->find($id)->update($data);
	}

	/**
	 * get company by id
	 *
	 * @param integer id
	 * @return object
	 */
	public function getCompanyById($id)
	{
		return $this->company->where('id', $id)->with('category')->with('country')->first();
	}

	/**
	 * edit company
	 *
	 * @param object $object
	 * @param array $data
	 * @return object
	 */
	public function editCompany($object, $data)
	{
		return $object->update($data);
	}

	/**
	 * delete company
	 *
	 * @param object $object
	 */
	public function deleteCompany($object)
	{
		return $object->delete();
	}

	/**
	 * search company
	 *
	 * @param array $searchDetails
	 * @param string $sort
	 * @param string $type
	 * @return array
	 */
	public function searchCompany($searchDetails, $sort, $type)
	{
		// get the search parameters
        $name = $searchDetails['company_search'];
        $country = $searchDetails['country'];
        if ($country) {
            // if ther is a country request, get the country object
            $countryObject = $this->country->where('name', $country)->first();
            // get the country id 
            $countryId = $countryObject->id;
        } else {
            $countryId = '';
        }
        $categoryId = $searchDetails['category'];
        $sectorId = $searchDetails['industry'];
		$search = $this->company;

		if($countryId && $countryId != '') {
			$search = $search->where('companies.country_id', $countryId);
		}

		if(isset($searchDetails['city']) && $searchDetails['city']) {
			$cityName = $searchDetails['city'];
			$search->whereHas('cities', function($query) use ($cityName) {
				$query->where('city', $cityName);
			});
		}

		$cloneSearch = $search;
		$cloneSearch = $cloneSearch->get();
		$genericsIds = [];
		foreach($cloneSearch as $result) {
			if($result->type == 'subsidiary') {
				$genericsIds[] = $result->generic->id;
			}else {
				$genericsIds[] = $result->id;
			}
		}
		$search = $this->company->whereIn('companies.id', $genericsIds);


		if($searchDetails['adminType'] != 'generic'){
			$id = $searchDetails['id'];
			$search = $search->whereHas('admins', function ($query) use ($id)  {
			    $query->where('user_id',  $id);
			});
		}
		
		if($name){
			$search = $search->where('companies.name', 'LIKE', '%'.$name.'%');
		}		

		if($categoryId){
			$search = $search->where('companies.category_id', $categoryId);
		}

		if($sectorId){
			$search = $search->where('companies.sector_id', $sectorId);
		}

		if($sort == 'name'){
			$results = $search->orderBy('name', $type)->select('*', 'companies.name as company_name','companies.id as company_id')->paginate(50);
		}elseif($sort == 'category'){
			$results = $search->join('category_translations', 'category_translations.category_id', '=', 'companies.category_id')->where('category_translations.locale', 'en')->orderBy('category_translations.name', $type)->select('*', 'companies.name as company_name','companies.id as company_id')->paginate(50);
		}elseif($sort == 'industry'){
			$results = $search->join('sector_translations', 'sector_translations.sector_id', '=', 'companies.sector_id')->where('sector_translations.locale', 'en')->orderBy('sector_translations.name', $type)->select('*', 'companies.name as company_name','companies.id as company_id')->paginate(50);
		}elseif($sort == 'date'){
			$results = $search->select('*', 'companies.name as company_name','companies.id as company_id')->paginate(50);
		}

		return $results;
	}

	/**
	 * get companies by country id
	 *
	 * @param integer $company
	 * @param integer $countryId
	 * @return array
	 */
	public function getCompaniesByCountry($company, $countryId)
	{
		$cities = [];

		$company = $this->company->where('id', $company)->first();
		if($company){

			if($company->type == 'generic')
			{
				$subsidiaries = $this->company->where('parent_id', '=', $company->id)->where('country_id', '=', $countryId)->where('sub_type', 'city_subsidiary')->get();
				
			}else {
				$generic = $company->generic;

				$subsidiaries = $this->company->where('parent_id', $generic->id)->where('country_id', $countryId)->where('sub_type', 'city_subsidiary')->get();
			}
			foreach ($subsidiaries as $key => $value) {
				
				if($value->city_name != '' && $value->country_id == $countryId)
				{
					$cities[] = [
								'city' => $value->city_name,
								'latitude' => $value->city_latitude,
								'longtitude' => $value->city_longtitude
									];
				}
			}
		}
		return $cities;
	}

	/**
	 * get country by parent id and country id
	 * 
	 * @param int $countryId
	 * @param int $parentId
	 * @return company object
	 */
	public function countrySubsidiary($countryId, $parentId)
	{
		$company = $this->company->where('country_id', $countryId)->where('parent_id', $parentId)->where('sub_type', 'country_subsidiary')->first();
		return $company;
	}


	/**
	 * Delete all company jobs
	 * if the company is generic, delete it's subsidiaries' jobs also
	 * 
	 * @param int $companyId
	 * 
	 */
	public function deleteAllJobs($companyId)
	{
		$company = $this->company->where('id', $companyId)->first();

		$jobs = $company->jobs()->delete();
		if($company->sub_type == 'generic') {
			$subsidiaries = $company->subsidiaries;
			
		}
		if(isset($subsidiaries))
		{
			foreach ($subsidiaries as $subsidiary) {
				$subsidiary->jobs()->delete();
				// if($subJobs)
				// {
				// 	foreach ($subJobs as $subJob) {
				// 		$this->job->where('id', $subJob->id)->delete();
				// 	}
				// }
			}
		}
		// if(count($jobs) >0 )
		// {
		// 	foreach ($jobs as $job) {
		// 		$this->job->where('id', $job->id)->delete();
		// 	}
		// }
	}

	/**
	 * get country subsidiary company by name and country id
	 *
	 * @param string $name
	 * @param integer $countryId
	 * @return object
	 */
	public function getCountrySubsidiary($name, $countryId, $latitude, $longitude)
	{
		$company = $this->company->where('name', $name)->where('type', 'subsidiary')->where('sub_type', 'country_subsidiary')->where('country_id', $countryId)->with('generic')->with('category')->with('sector')->first();
		if(!$company) {
			$data = ['error' => 'no company'];
			return $data;
		}
		$cityCount = $company->cities()->count();
		if ($cityCount <= 4) {
			$company->citiesOperatingCount = 0;
		} else {
			$company->citiesOperatingCount = $cityCount-4;
		}
		$company->citiesOperating = $company->cities()->select('city', 'company_id', 'latitude', 'longitude', \DB::raw("( 6371 * acos( cos( radians(".$latitude.") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(".$longitude.") ) + sin( radians(".$latitude.") ) * sin( radians( latitude ) ) ) ) AS distance"))->orderBy('distance', 'asc')->take(4)->get();
		return $company;
	}

	/**
	 * get top sector companies
	 *
	 * @param Request $request
	 * @param integer $id
	 * @param integer $country_id
	 * @param float $latitude
	 * @param float $longtitude
	 * @return array
	 */
	public function getTopSectorCountrySubsidiaries($request, $id, $country_id, $latitude, $longtitude)
	{
		$distance = config('app.search_distance');
		$companies = $this->company->where('companies.sector_id', $id)
								   ->where('companies.country_id', $country_id)
								   ->where('companies.type', 'subsidiary')
								   ->where('companies.sub_type', 'country_subsidiary');
		$companies = $companies->where(function ($query) {
                $query->whereNull('companies.restrict')
                      ->orWhere('companies.restrict', '');
            })
			->whereHas('jobs', function($query) {
				$query->whereNull('jobs.restrict')
					  ->orWhere('jobs.restrict', '!=', 'true');
			})
			->with('generic')->with('sector');
		$companies->leftJoin('company_cities', 'companies.id', '=', 'company_cities.company_id');
		$companies->select(['companies.*', 'company_cities.latitude', 'company_cities.longitude', \DB::raw("( 6371 * acos( cos( radians(".$latitude.") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(".$longtitude.") ) + sin( radians(".$latitude.") ) * sin( radians( latitude ) ) ) ) AS distance")])->orderByRaw('-distance desc');
		if($request['keyword']){
			$companies = $this->filterByKeword($companies, $request['keyword']);
		}

		$tempSearch = clone $companies;
		$count = count($tempSearch->get()->unique('id'));
		$companies = $companies->with('jobs')->with('country')->with('cities')->get()->unique('id')->forPage(($request['count'])+1, 10);
		$companies->map(function($company) use($latitude, $longtitude){
			$cityCount = $company->cities()->count();
			if ($cityCount <= 4) {
				$company->citiesOperatingCount = 0;
			} else {
				$company->citiesOperatingCount = $cityCount-4;
			}
			$company->citiesOperating = $company->cities()->select('city', 'company_id', 'latitude', 'longitude', \DB::raw("( 6371 * acos( cos( radians(".$latitude.") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(".$longtitude.") ) + sin( radians(".$latitude.") ) * sin( radians( latitude ) ) ) ) AS distance"))->orderBy('distance', 'asc')->take(4)->get();
		});
		$dataArray = [
			'pages_count' => $count,
			'companies'  => $companies,
		];

		return $dataArray;
	}

	/**
	 * get top category companies
	 *
	 * @param Request $request
	 * @param integer $id
	 * @param integer $country_id
	 * @param float $latitude
	 * @param float $longtitude
	 * @return array
	 */
	public function getTopCategoryCountrySubsidiaries($request, $id, $country_id, $latitude, $longtitude)
	{
		$distance = config('app.search_distance');
		$companies = $this->company
								   ->where('companies.country_id', $country_id)
								   ->where('companies.type', 'subsidiary')
								   ->where('companies.sub_type', 'country_subsidiary')
								   ->where(function($query) use($id){
								   		$query->where('companies.category_id', $id)
								   			  ->orWhereHas('jobs', function($query) use($id){
									   					$query->where('category_id', $id);
									   				});
								   });
		$companies = $companies->where(function ($query) {
                $query->whereNull('companies.restrict')
                      ->orWhere('companies.restrict', '!=', 'true');
            })
			->whereHas('jobs', function($query) {
				$query->whereNull('jobs.restrict')
					  ->orWhere('jobs.restrict', '!=', 'true');
			})
		->with('generic')->with('sector');
		$companies->leftJoin('company_cities', 'companies.id', '=', 'company_cities.company_id');
		$companies->select(['companies.*', 'company_cities.latitude', 'company_cities.longitude', \DB::raw("( 6371 * acos( cos( radians(".$latitude.") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(".$longtitude.") ) + sin( radians(".$latitude.") ) * sin( radians( latitude ) ) ) ) AS distance")])

			->orderByRaw('-distance desc');

		// DB::raw(["select * from (select 'companies.*', 'company_cities.latitude', 'company_cities.longitude', ( 6371 * acos( cos( radians(".$latitude.") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(".$longtitude.") ) + sin( radians(".$latitude.") ) * sin( radians( latitude ) ) ) ) AS distance GROUP BY 'companies.id')  ORDER BY distance"]);

		$tempSearch = clone $companies;
		$count = count($tempSearch->get()->unique('id'));
		$companies = $companies->with('jobs')->with('country')->get()->unique('id')->forPage(($request['count'])+1, 10);
		$companies->map(function($company) use($latitude, $longtitude){
			$cityCount = $company->cities()->count();
			if ($cityCount <= 4) {
				$company->citiesOperatingCount = 0;
			} else {
				$company->citiesOperatingCount = $cityCount-4;
			}
			$company->citiesOperating = $company->cities()->select('city', 'company_id', 'latitude', 'longitude', \DB::raw("( 6371 * acos( cos( radians(".$latitude.") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(".$longtitude.") ) + sin( radians(".$latitude.") ) * sin( radians( latitude ) ) ) ) AS distance"))->orderBy('distance', 'asc')->take(4)->get();
		});
		$dataArray = [
			'pages_count' => $count,
			'companies'  => $companies,
		];


  //  		DB::select(DB::raw(‘select * from (select * from messages group by fromId desc) m order by m.createdAt’));

		return $dataArray;
	}

	/**
	 * get companies by location
	 *
	 * @param float $latitude
	 * @param float $longitude
	 * @param integer $count
	 * @param integer $country_id
	 * @return array
	 */
	public function getCitySubsidiariesByLocation($latitude, $longtitude, $count, $country_id)
	{
		$distance = config('app.search_distance'); 
		$search = $this->company->where('companies.country_id', $country_id)->where(function($query){
			$query->whereNull('companies.restrict')
				  ->orwhere('companies.restrict', '!=', 'true'); 	
		})->where('companies.type', 'subsidiary')
		->where('companies.sub_type', 'country_subsidiary')
		->whereHas('jobs', function($query) {
				$query->whereNull('jobs.restrict')
					  ->orWhere('jobs.restrict', '!=', 'true');
			});
		// ->with(['generic', 'country', 'sector', 'cities' => function ($query)  use($latitude, $longtitude) {
		// 	return $query->select('city', 'company_id', 'latitude', 'longitude', \DB::raw("( 6371 * acos( cos( radians(".$latitude.") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(".$longtitude.") ) + sin( radians(".$latitude.") ) * sin( radians( latitude ) ) ) ) AS distance"))->orderBy('distance', 'asc')->take(4)->get();
		// }]);
		// $basicQuery = $search
		// ->join('company_cities', 'companies.id', '=', 'company_cities.company_id')
		// ->select(
		// 	'companies.*',
		// 	\DB::raw("MIN( ( 6371 * acos( cos( radians(".$latitude.") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(".$longtitude.") ) + sin( radians(".$latitude.") ) * sin( radians( latitude ) ) ) ) ) AS distance"),
		// 	\DB::raw("COUNT(company_cities.id) as citiesOperatingCount")
		// )
		// ->whereRaw("( 6371 * acos( cos( radians(".$latitude.") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(".$longtitude.") ) + sin( radians(".$latitude.") ) * sin( radians( latitude ) ) ) ) < ".$distance)
		// ->orderByRaw("( 6371 * acos( cos( radians(".$latitude.") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(".$longtitude.") ) + sin( radians(".$latitude.") ) * sin( radians( latitude ) ) ) ) asc")
		// ->groupBy("company_cities.company_id");
		
		// return $basicQuery->paginate(10);

		/************************************/
		$search->leftJoin('company_cities', 'companies.id', '=', 'company_cities.company_id');
		$sub = $search->select(['companies.*', 'company_cities.latitude', 'company_cities.longitude', \DB::raw("( 6371 * acos( cos( radians(".$latitude.") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(".$longtitude.") ) + sin( radians(".$latitude.") ) * sin( radians( latitude ) ) ) ) AS distance")])->having('distance', '<', $distance)->orderBy('distance', 'asc');
		$tempSearch = clone $search;
		$allCount = count($tempSearch->get()->unique('id'));
		$companies = $search->with(['generic', 'country', 'sector'])->get()->unique('id')->forPage($count+1, 10);
		$companies->map(function($company) use($latitude, $longtitude){
			$companyQuery = $company->cities();
			$cityCount = $companyQuery->count();
			if ($cityCount <= 4) {
				$company->citiesOperatingCount = 0;
			} else {
				$company->citiesOperatingCount = $cityCount-4;
			}
			$company->citiesOperating = $company->cities()->select('city', 'company_id', 'latitude', 'longitude', \DB::raw("( 6371 * acos( cos( radians(".$latitude.") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(".$longtitude.") ) + sin( radians(".$latitude.") ) * sin( radians( latitude ) ) ) ) AS distance"))->orderBy('distance', 'asc')->take(4)->get();
		});
		$dataArray = [
			'count' => $allCount,
			'companies' => $companies
		];
		return $dataArray;
	}

	/**
	 * get companies by location and name
	 *
	 * @param float $latitude
	 * @param float $longitude
	 * @param integer $count
	 * @param integer $country_id
	 * @param string $name
	 * @return array
	 */
	public function getCitySubsidiariesByLocationAndName($latitude, $longtitude, $count, $country_id, $name) 
	{
		$distance = config('app.search_distance');
		// $search = $this->company->getCompaniesByLocations($latitude, $longtitude);
		$search = $this->company->where('companies.country_id', $country_id)
						 ->where('companies.type', 'subsidiary')
						 ->where('companies.sub_type', 'country_subsidiary')
						 ->where(function($query){
									$query->whereNull('companies.restrict')
										  ->orwhere('companies.restrict', '!=', 'true'); 	
							})->where(function($query) use ($name) {
									$query->where(function($query) use ($name){
										$query->where('name', 'LIKE', '%'.$name.'%')
											    ->orWherehas('jobs', function($query) use ($name) {
											  		$query->where('name', 'LIKE', '%'.$name.'%')
											  		->orWhereHas('categoryTranslation', function($query) use ($name) {
														$query->where('name', 'LIKE', '%'.$name.'%');
													})->orWhereHas('sectorTranslation', function($query) use ($name) {
														$query->where('name', 'LIKE', '%'.$name.'%');
													});
											  	});
											  	})->orWhereHas('categoryTranslation', function($query) use ($name) {
													$query->where('name', 'LIKE', '%'.$name.'%');
												})->orWhereHas('sectorTranslation', function($query) use ($name) {
													$query->where('name', 'LIKE', '%'.$name.'%');
												})->orwhere('description', 'LIKE', '%'.$name.'%');
								})
							->whereHas('jobs', function($query) {
								$query->whereNull('jobs.restrict')
									  ->orWhere('jobs.restrict', '!=', 'true');
							});
		$search->leftJoin('company_cities', 'companies.id', '=', 'company_cities.company_id');
		$search->select(['companies.*', 'company_cities.latitude', 'company_cities.longitude', \DB::raw("( 6371 * acos( cos( radians(".$latitude.") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(".$longtitude.") ) + sin( radians(".$latitude.") ) * sin( radians( latitude ) ) ) ) AS distance")])->having('distance', '<', $distance)->orderBy('distance', 'asc');

		$tempSearch = clone $search;
		$allCount = count($tempSearch->get()->unique('id'));
		$companies = $search->with('country')->with('generic')->with('sector')->get()->unique('id')->forPage($count+1, 10);
		$companies->map(function($company) use($latitude, $longtitude){
			$companyQuery = $company->cities();
			$cityCount = $companyQuery->count();
			if ($cityCount <= 4) {
				$company->citiesOperatingCount = 0;
			} else {
				$company->citiesOperatingCount = $cityCount-4;
			}
			$company->citiesOperating = $company->cities()->select('city', 'company_id', 'latitude', 'longitude', \DB::raw("( 6371 * acos( cos( radians(".$latitude.") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(".$longtitude.") ) + sin( radians(".$latitude.") ) * sin( radians( latitude ) ) ) ) AS distance"))->orderBy('distance', 'asc')->take(4)->get();
		});
		$dataArray = [
			'count' => $allCount,
			'companies' => $companies
		];
		return $dataArray;

	}

	/**
	 * browse jobs gigs companies
	 * 
	 */
	public function getBrowseJobsGigs($latitude, $longtitude, $count, $country_id, $keyword)
	{
		$distance = config('app.search_distance');

		$search = $this->company->where(function($query){
			$query->whereNull('companies.restrict')
				  ->orwhere('companies.restrict', '!=', 'true'); 	
		})->where('companies.country_id', $country_id)->where('companies.type', 'subsidiary')->where('companies.sub_type', 'country_subsidiary')
			->whereHas('jobs', function($query) {
				$query->whereNull('jobs.restrict')
					  ->orWhere('jobs.restrict', '!=', 'true');
			})
			->with('generic')->with('sector');

		$search->leftJoin('company_cities', 'companies.id', '=', 'company_cities.company_id');
		$search->select(['companies.*', 'company_cities.latitude', 'company_cities.longitude', \DB::raw("( 6371 * acos( cos( radians(".$latitude.") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(".$longtitude.") ) + sin( radians(".$latitude.") ) * sin( radians( latitude ) ) ) ) AS distance")])->orderByRaw('-distance desc');

		if($keyword){
			$search = $this->filterByKeword($search, $keyword);
		}
		$tempSearch = clone $search;
		$allCount = count($tempSearch->get()->unique('id'));
		$companies = $search->with('country')->get()->unique('id')->forPage($count+1, 10);
		$companies->map(function($company) use($latitude, $longtitude){
			$cityCount = $company->cities()->count();
			if ($cityCount <= 4) {
				$company->citiesOperatingCount = 0;
			} else {
				$company->citiesOperatingCount = $cityCount-4;
			}
			$company->citiesOperating = $company->cities()->select('city', 'company_id', 'latitude', 'longitude', \DB::raw("( 6371 * acos( cos( radians(".$latitude.") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(".$longtitude.") ) + sin( radians(".$latitude.") ) * sin( radians( latitude ) ) ) ) AS distance"))->orderBy('distance', 'asc')->take(4)->get();
		});
		
		$dataArray = [
			'count' => $allCount,
			'companies' => $companies
		];
		return $dataArray;
	}

	public function getCountrySubsidiaries()
	{
		return $this->company->where('sub_type', 'country_subsidiary')->where(function($query) {
			$query->whereNull('restrict')
				  ->orwhere('restrict', '!=', 'true');
		})->orderBy('name', 'asc')->with('country')->get();
	}

	public function getPublishedCompanies()
	{
		return $this->company->where('sub_type', 'country_subsidiary')->where(function($query) {
			$query->whereNull('restrict')
				  ->orwhere('restrict', '!=', 'true');})->with('country')->get();
	}

	public function getAllGerenicsTodayCount()
	{
		return $this->company->where('type', 'generic')->whereDate('created_at', DB::raw('CURDATE()'))->count();
	}
}