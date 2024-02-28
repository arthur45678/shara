<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Requests\RegistrationRequest;
use App\Http\Requests\FirstStepUserCreateRequest;
use App\Http\Requests\TablesRequest;
use App\Http\Controllers\Controller;
use Dingo\Api\Routing\Helpers;
use App\Contracts\CompanyInterface;
use App\Contracts\CategoryInterface;
use App\Contracts\CountryInterface;
use App\Contracts\SectorInterface;
use App\Contracts\UserInterface;
use App\Contracts\JobInterface;
use App\Contracts\CityInterface;
use App\Contracts\MailInterface;
use App\Contracts\LanguageInterface;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException; 
use Sentinel;
use Activation;
use User;
use File;
use Exception;

class SearchController extends Controller
{
    use Helpers;

    /**
     * Object of UserInterface class
     *
     * @var userRepo
     */
    private $userRepo;

    /**
     * Object of CompanyInterface class
     *
     * @var companyRepo
     */
    private $companyRepo;


    /**
     * Object of CategoryInterface class
     *
     * @var categoryRepo
     */
    private $categoryRepo;

    /**
     * Object of CountryInterface class
     *
     * @var countryRepo
     */
    private $countryRepo;

    /**
     * Object of UserInterface class
     *
     * @var userRepo
     */
    private $sectorRepo;

    /**
     * Object of CityInterface class
     *
     * @var cityRepo
     */
    private $cityRepo;

    /**
     * Object of JobInterface class
     *
     * @var jobRepo
     */
    private $jobRepo;

    /**
     * Object of MailInterface class
     *
     * @var mailRepo
     */
    private $mailRepo;

    /**
     * Object of LanguageInterface class
     *
     * @var languageRepo
     */
    private $languageRepo;

    /** 
     * Create a new instance of Controller class.
     *
     * @param CompanyInterface $companyRepo
     * @return void
     */
	public function __construct(CompanyInterface $companyRepo, CategoryInterface $categoryRepo, CountryInterface $countryRepo, SectorInterface $sectorRepo, CityInterface $cityRepo, JobInterface $jobRepo, UserInterface $userRepo, MailInterface $mailRepo, LanguageInterface $languageRepo)
	{
        // $this->middleware("cors");
        $this->companyRepo = $companyRepo;
        $this->categoryRepo = $categoryRepo;
		$this->countryRepo = $countryRepo;
        $this->sectorRepo = $sectorRepo;
        $this->cityRepo = $cityRepo;
        $this->jobRepo = $jobRepo;
        $this->userRepo = $userRepo;
        $this->mailRepo = $mailRepo;
        $this->languageRepo = $languageRepo;
	}


    /**
     * get user latitde and longitude
     *
     * @param $request
     * @return array
     */
    private function getUserLatLng($request)
    {
        try {
            //getting user's ip from the request
            $ip = $request->ip();
            //getting countryCode and record from the ip
            $record = geoip_record_by_name($ip);
            $countryCode = strtolower( $record['country_code'] );
            if ($record) {
                //defining default city and country
                $cityDefault = $record['city'];
                $countryDefault = $record['country_name'];
                if(!$cityDefault) {
                    $locationDefault = $countryDefault;
                }else {
                    $locationDefault = $cityDefault.', '.$countryDefault;
                }
                //getting latitude, longitude and contry object
                $latitude = $record['latitude'];
                $longtitude = $record['longitude'];
                $country = $this->countryRepo->getCountryByLocale($countryCode);
                if($country){
                    $countryName = $country->name;
                }else{
                    $countryName = "";
                }
                
            } else {
                //if couldn't get record from geoip, setting some default values
                $latitude = '';
                $longtitude = '';
                // $countryCode = 'unknown'; 
                $countryName = '';
                $unknown = true;
            }
            // }
            $details =  [
                'longtitude' => $longtitude,
                'latitude' => $latitude,
                'country' => $countryCode,
                'countryName' => $countryName 
            ];
            if(isset($unknown)) {
                $details['unknown'] = true;
            }
            //if location changed from user's location, set all location info
            if($request->lang !== 'default' && $request->lang !== '') {
               $code = strtoupper($request->lang);
               $localization = $this->countryRepo->getCountryByLocale($code);
               if(!$localization) {
                $localization = $this->countryRepo->getCountryByLocale('it');
               }
               $details['defaultLatitude'] = $localization->latitude;
               $details['defaultLongitude'] = $localization->longitude;
               $details['defaultCountryCode'] = $localization->abbreviation;
               $details['defaultCountryName'] = $localization->name;
               $details['defaultLocation'] = $localization->name;

            }

            if ($request->lang !== 'default' && $request->lang !== '') {
               $code = strtoupper($request->lang);
               $localization = $this->countryRepo->getCountryByLocale($code);
               if(!$localization) {
                $localization = $this->countryRepo->getCountryByLocale('it');
               }
               if($details['countryName'] != $localization->name){
                    $details['defaultLocation'] = $localization->capital.', '.$localization->name;
                    $details['defaultLatitude'] = $localization->latitude;
                    $details['defaultLongitude'] = $localization->longitude;
                    $details['defaultCountryCode'] = $localization->abbreviation;
                    $details['defaultCountryName'] = $localization->name;
                    $details['defaultCityName'] = $localization->capital;
                }else{
                    $details['defaultLatitude'] = $latitude;
                    $details['defaultLongitude'] = $longtitude;
                    $details['defaultCountryCode'] = $countryCode;
                    $details['defaultCountryName'] = $countryName;
                    $details['defaultCityName'] = $cityDefault;
                }
            }

            return $details;
        } catch (Exception $e) {
            dd($e);
            \Log::error($e);
        }
                
    }

    /**
     * check if user applied for job or not
     *
     * @param request
     * @return array
     */
    private function getCheckIfApplied($companies)
    {
        $locale = \App::getLocale();
        //get company category and company jobs category
        foreach ($companies as $comp) {
            $comp['description'] = nl2br($comp['description']);
            // ifcompany has category, get the category
            $jobCategories = [];
            // if($comp->category){
            //     $compCategory = $comp->category;
            //     if($compCategory->getTranslation($locale, true)->name){
            //         $compCategory->name = $compCategory->getTranslation($locale, true)->name;

            //     }else{
            //         $compCategory->name = $compCategory->getTranslation('en', true)->name;
            //     }
            //     $jobCategories[] = $compCategory;
            // }

            //check if the company has jobs
            if($comp->has('jobs', '>', '0')){
                // if yes, for each job get it's category
                foreach ($comp->jobs as $companyJob) {
                    if ($companyJob->category) {
                        $category = $companyJob->category;
                        if($category->getTranslation($locale, true)->name){
                            $category->name = $category->getTranslation($locale, true)->name;

                        }else{
                            $category->name = $category->getTranslation('en', true)->name;
                        }
                        if(!in_array($category, $jobCategories)){
                            $jobCategories[] = $category;
                        }              
                    }
                }
            }
            $comp['jobCategories'] = $jobCategories;
        }

        try {
            //if user is autenticated, check if user has applied for jobs
            $user = \JWTAuth::parseToken()->authenticate();
            foreach ($companies as $company) {
                if((count($user->applications) > 0) && $company['job']) {
                    if($user->applications->contains($company['job']['id'])){
                        $company['apply'] = false;
                    }else{
                        $company['apply'] = true;
                    }
                }else if((count($user->applications) == 0) && $company['job']) {
                    $company['apply'] = true;
                }
            }
        } catch (Exception $e) {
            \Log::info($e->getMessage());
            foreach ($companies as $company) {
                $company['apply'] = true;
            }
        }

        return $companies;
    }

	/**
     * search by location
     *
     * @Get("/search")
     */
    public function getSearchByLocation(TablesRequest $request)
    {
        $requestParams = $request->all();
        $details = [];
        if ($request) {
            //taking serach text, lattitude, longitude, country abbreviation from selected location
            $search_text = $request->search_text;
            $latitude = $request->latitude;
            $longitude = $request->longitude;
            $country_short_name = strtolower($request->country_short_name);
            if (!$search_text && $latitude && $longitude) {
                //the case with location and no search text
                $country = $this->countryRepo->getCountryByLocale($country_short_name);
                if ($country) {
                    $country_id = $country->id;
                    $count = $request->count;
                    //companies within N km from the selected location
                    $results = $this->companyRepo->getCitySubsidiariesByLocation($latitude, $longitude, $count, $country_id);
                    $companies = $results['companies'];
                    $count_pages = $results['count'];
                    $companies_objects = $companies;
                    if (count($companies) > 0) {
                        foreach ($companies_objects as $company) {
                            //if company has sector, set default sector name in english, in case it doesn't have translation
                            if ($company->sector) {
                                $company->sector->defaultSector =  $company->sector->getTranslation('en', true);
                            }
                        }
                        $companies_objects = $this->getCheckIfApplied($companies_objects);
                        $data = [
                            'companies' => $companies_objects,
                            'pages_count' => $count_pages,
                            'requestParams' => $requestParams
                        ];
                    }else {
                        $data = ['error'=>'no companies', 'status' => 'error_no_companies', 'requestParams' => $requestParams];
                    }
                }else {
                    $data = ['error' => 'no companies', 'status' => 'error_no_companies', 'requestParams' => $requestParams];
                    return response()->json($data);
                }
                
                
            } else if ($search_text && $latitude && $longitude) {
                    //case with search text and location
                    $country = $this->countryRepo->getCountryByLocale($country_short_name);
                    $country_id = $country->id;
                    $count = $request->count;
                    //companies within N km from the selected location, and having either name, or job name, or sector, or category, or description according to search text
                    $results = $this->companyRepo->getCitySubsidiariesByLocationAndName($latitude, $longitude, $count, $country_id, $search_text);
                    $companies = $results['companies'];
                    $count_pages = $results['count'];
                    $companies_objects = $companies;
                    if (count($companies) > 0) {
                        foreach ($companies_objects as $company) {
                            if ($company->sector) {
                                //if company has sector, set default sector name in english, in case it doesn't have translation
                                $company->sector->defaultSector =  $company->sector->getTranslation('en', true);
                            }
                        }
                        $companies_objects = $this->getCheckIfApplied($companies_objects);
                        $data = [
                            'companies' => $companies_objects,
                            'pages_count' => $count_pages,
                            'requestParams' => $requestParams
                        ];
                    }else{
                        $data = ['error'=>'no companies', 'status' => 'error_no_companies', 'requestParams' => $requestParams];
                    }
            
            
            } else if ($search_text && !$latitude && !$longitude) {
                    //case with search text and no location
                    //determine location for searching 
                    $details = $this->getUserLatLng($request);
                    $requestParams['mainCountry'] = $details['countryName'];
                    $longitude = $details['longtitude'];
                    $latitude = $details['latitude'];
                    $country = $this->countryRepo->getCountryByLocale($details['country']);
                    $country_id = $country->id;
                    $count = $request->count;
                    //companies within N km from the determined location, and having either name, or job name, or sector, or category, or description according to search text
                    $results = $this->companyRepo->getCitySubsidiariesByLocationAndName($latitude, $longitude, $count, $country_id, $search_text);
                    $companies = $results['companies'];
                    $count_pages = $results['count'];
                    $companies_objects = $companies;
                    if(count($companies) > 0) {
                        foreach($companies_objects as $company) {
                            if ($company->sector) {
                                //if company has sector, set default sector name in english, in case it doesn't have translation
                                $company->sector->defaultSector =  $company->sector->getTranslation('en', true);
                            }
                        }
                        $companies_objects = $this->getCheckIfApplied($companies_objects);
                        $data = [
                            'companies' => $companies_objects,
                            'pages_count' => $count_pages,
                            'requestParams' => $requestParams
                        ];
                    } else {
                        $data = ['error'=>'no companies', 'status' => 'error_no_companies', 'requestParams' => $requestParams];
                    }
            }else{

                $data = ['error'=>'empty request'];
            }
            $data['details'] = $details;
            $data['requestParams'] = $requestParams;
            return $this->response->array($data);
        } else {
            return response()->json(['error' => 'empty request']);
        }
    }

    /**
     * Get coampnies of popular categories
     *
     * @Get('/popular-categories')
     */
    public function getPopularCategories(TablesRequest $request) 
    {
        //set country code for search
        if($request->lang) {
            $countryCode = $request->lang;
        }else {
            $countryCode = $request->countryCode;
        }
        $requestParams = $request->all();
        $categoryId = $request->id;
        $requestParams['category_id'] = $categoryId;
        //get location details
        $details = $this->getUserLatLng($request);
        if($request->latitude && $request->longitude){
            //if request has latitude and longitude, get companies with that location
            $latitude = $request['latitude'];
            $longtitude = $request['longitude'];
            $country = $this->countryRepo->getCountryByLocale(strtoupper($countryCode));
        }else{
            //otherwise get location from details
            $latitude = $details['defaultLatitude'];
            $longtitude = $details['defaultLongitude'];
            $country = $this->countryRepo->getCountryByLocale($countryCode);
            $requestParams['latitude'] = $latitude;
            $requestParams['longitude'] = $longtitude;
            $requestParams['country_name'] = $country->name;
        }
        //get companies with requested category and location
        $companies = $this->companyRepo->getTopCategoryCountrySubsidiaries($request->inputs(), $categoryId, $country->id, $latitude, $longtitude);
        if(count($companies['companies']) > 0) {
            foreach($companies['companies'] as $company) {
                if ($company->sector) {
                    //if company has sector, set default sector name in english, in case it doesn't have translation
                    $company->sector->defaultSector =  $company->sector->getTranslation('en', true);
                }
                $company->category->defaultCategory =  $company->category->getTranslation('en', true);

            }
            $companies['companies'] = $this->getCheckIfApplied($companies['companies']);
            $companies['requestParams'] = $requestParams;
            $companies['breadcrumb'] = $company->category->name ? $company->category->name : $company->category->defaultCategory->name;
            return response()->json($companies);
        }else {
            return response()->json(['error' => 'no results', 'requestParams' => $requestParams]);
        }
    }

    /**
     * Get companies of top sector
     *
     * @Get('/top-sectors')
     */
    public function getTopSectors(TablesRequest $request)
    {
        //set country code for search
        if($request->lang) {
            $countryCode = $request->lang;
        }else {
            $countryCode = $request->countryCode;
        }
        $requestParams = $request->all();
        $sectorId = $request->id;
        $requestParams['sector_id'] = $sectorId;
        //get location details
        $details = $this->getUserLatLng($request);
        if($request->latitude && $request->longitude){
            //if request has latitude and longitude, get companies with that location
            $latitude = $request['latitude'];
            $longitude = $request['longitude'];
            $country = $this->countryRepo->getCountryByLocale(strtoupper($request['lang']));
        }else{
            //otherwise get location from details
            $latitude = $details['defaultLatitude'];
            $longitude = $details['defaultLongitude'];
            $country = $this->countryRepo->getCountryByLocale($countryCode);
            $requestParams['latitude'] = $latitude;
            $requestParams['longitude'] = $longitude;
            $requestParams['country_name'] = $country->name;
        }

        //get companies with requested category and location
        $companies = $this->companyRepo->getTopSectorCountrySubsidiaries($request->inputs(), $sectorId, $country->id, $latitude, $longitude);
        if (count($companies['companies']) > 0) {
            foreach($companies['companies'] as $company) {
                if ($company->sector) {
                    //if company has sector, set default sector name in english, in case it doesn't have translation
                    $company->sector->defaultSector =  $company->sector->getTranslation('en', true);
                }
            }
            $companies['companies'] = $this->getCheckIfApplied($companies['companies']);
            $companies['requestParams'] = $requestParams;
            $companies['breadcrumb'] = $company->sector->name ? $company->sector->name : $company->sector->defaultSector->name;
            return response()->json($companies);
        }else{
            return response()->json(['error' => 'no results', 'requestParams' => $requestParams]);
        }
        
    }

    /**
     * Get 
     *
     * @Get('/browse-jobs-gigs')
     */
    public function getBrowseJobsGigs(Request $request)
    {
        $requestParams = $request->all();
        //get location details
        $details = $this->getUserLatLng($request);
        if($request->latitude && $request->longitude){
            //if request has latitude and longitude, get companies with that location
            $latitude = $request['latitude'];
            $longtitude = $request['longitude'];
            $country = $this->countryRepo->getCountryByLocale(strtoupper($request['lang']));
        }else{
            //otherwise get location from details
            $latitude = $details['defaultLatitude'];
            $longtitude = $details['defaultLongitude'];
            $country = $this->countryRepo->getCountryByLocale($details['defaultCountryCode']);
        }
        $requestParams['country_name'] = $country->name;
        $requestParams['cityName'] = $details['defaultCityName'];
        $requestParams['activationAvailable'] = true;
        $country_id = $country->id;
        $count = $request->count;
        if(isset($request->page)) {
            $count = $request->page - 1;
        }
        //get companies in given country, ordered by distance from searched location
        $results = $this->companyRepo->getBrowseJobsGigs($latitude, $longtitude, $count, $country_id, $request->keyword);
        $count_pages = $results['count'];
        $companies_objects = $results['companies'];
        if($companies_objects) {
            foreach($companies_objects as $company) {
                if ($company->sector) {
                    //if company has sector, set default sector name in english, in case it doesn't have translation
                    $company->sector->defaultSector =  $company->sector->getTranslation('en', true);
                }
            }           
            $companies_objects = $this->getCheckIfApplied($companies_objects);
            $data = [
                'companies' => $companies_objects,
                'pages_count' => $count_pages,
                'requestParams' => $requestParams
            ];
        }else{
            $data = ['error'=>'no companies', 'requestParams' => $requestParams];
        }
        return response()->json($data);
    }
}
