<?php

namespace App\Services;

use App\Contracts\JobInterface;
use Illuminate\Support\Facades\DB; 
use App\Job;
use App\Category;
use App\Country;
use App\Company;
use App\EmailJob;

class JobService implements JobInterface
{
	/**
	 * Object of Job class.
	 *
	 * @var $job 
	 */
	private $job;

	/**
	 * Create a new instance of JobService class.
	 *
	 * @return void
	 */

	public function __construct()
	{
		$this->job = new Job(); 
		$this->category = new Category(); 
		$this->country = new Country();
		$this->company = new Company();
		$this->emailjob = new EmailJob();

	}

	public function getAllJobs()
	{
		return $this->job->all();
	}

	public function getAllJobsPaginate($sort, $type)
	{
		if($sort == 'name'){
			$jobs = $this->job->orderBy('name', $type)->select('*', 'jobs.name as job_name', 'jobs.id as jobId')->paginate(50);
		}elseif($sort == 'company')
		{
			$jobs = $this->job->join('companies', 'companies.id', '=', 'jobs.company_id')->orderBy('companies.name', $type)->select('*', 'jobs.name as job_name', 'jobs.id as jobId')->paginate(50);
		}elseif($sort == 'country'){
			$jobs = $this->job->join('countries', 'countries.id', '=', 'jobs.country_id')->orderBy('countries.name', $type)->select('*', 'jobs.name as job_name', 'jobs.id as jobId')->paginate(50);
		}elseif($sort == 'city'){
			$jobs = $this->job->where('city_name', '!=', '')->orderBy('city_name', $type)->select('*', 'jobs.name as job_name', 'jobs.id as jobId')->paginate(50);
		}elseif($sort == 'date'){
			$jobs = $this->job->select('*', 'jobs.name as job_name', 'jobs.id as jobId')->paginate(50);
		}
		return $jobs;
	}

	public function createJob($data)
	{
		return $this->job->create($data);
	}

	public function getJobById($id)
	{
		return $this->job->where('id', $id)->first();
	}

	public function editJob($object, $data)
	{
		return $object->update($data);
	}

	public function deleteJob($object)
	{
		return $object->delete();
	}

	public function searchJob($name, $country_id, $city, $category_id, $sector_id, $sort, $type)
	{
		$search = $this->job;
		if($name)
		{
			$search = $search->where('name', 'LIKE', '%'.$name.'%');
		}
		if($country_id)
		{
			$search = $search->where('country_id', $country_id);
		}
		if($city)
		{
			$search = $search->where('city_name', $city);
		}
		if($category_id)
		{
			$search = $search->where('category_id', $category_id);
		}
		if($sector_id)
		{
			$search = $search->where('sector_id', $sector_id);
		}

		if($sort == 'name'){
			$jobs = $this->job->orderBy('name', $type)->select('*', 'jobs.name as job_name', 'jobs.id as jobId')->paginate(50);
		}elseif($sort == 'company')
		{
			$jobs = $search->join('companies', 'companies.id', '=', 'jobs.company_id')->orderBy('companies.name', $type)->select('*', 'jobs.name as job_name', 'jobs.id as jobId')->paginate(50);
		}elseif($sort == 'country'){
			$jobs = $search->join('countries', 'countries.id', '=', 'jobs.country_id')->orderBy('countries.name', $type)->select('*', 'jobs.name as job_name', 'jobs.id as jobId')->paginate(50);
		}elseif($sort == 'city'){
			$jobs = $search->where('city_name', '!=', '')->orderBy('city_name', $type)->select('*', 'jobs.name as job_name', 'jobs.id as jobId')->paginate(50);
		}elseif($sort == 'date'){
			$jobs = $search->select('*', 'jobs.name as job_name', 'jobs.id as jobId')->paginate(50);
		}
		return $jobs;
	}

	public function getCompanyJobs($company_id)
	{
		return $this->job->where('company_id', $company_id)->where(function($query) {
			$query->whereNull('restrict')
                  ->orWhere('restrict', '!=', 'true');
        })->get();
	}

	public function getUnsentJobs()
	{
		$jobs = $this->job->where('is_sent', false)->get();
		return $jobs;
	}

	/**
     * get jobs by subscribtion details
     */
    // public function getJobsBySubscribtionData($data)
    // {
    //     $country = $this->country->where('name', $data['country'])->first();

    //     $jobsId = DB::select("SELECT id, ( 6371 * acos( cos( radians(".$data['latitude'].") ) * cos( radians(".$data['latitude'].") ) * cos( radians(".$data['longtitude'].") - radians(".$data['longtitude'].") ) + sin( radians(".$data['latitude'].") ) * sin( radians(".$data['latitude'].") ) ) ) AS distance FROM jobs WHERE country_id = ".$country->id." HAVING distance < 50 ORDER BY distance");
    //     $jobsId = collect($jobsId)->pluck('id')->toArray();

    //     $jobs = $this->job->whereIn('jobs.id', $jobsId);
    //         $name = $data['keyword'];
    //         $categoryId = $data['category_id'];
    //         $sectorId = $data['sector_id'];
    //         $jobs = $jobs->where('restrict', '!=', 'true')->where('is_sent', false)->where(function($query) use ($name, $categoryId, $sectorId) {
    //                     $query->where(function($query) use ($name){
    //                         $query->where('name', 'LIKE', '%'.$name.'%')
    //                                 ->orWherehas('company', function($query) use ($name) {
    //                                       $query->where('name', 'LIKE', '%'.$name.'%');
    //                                   });
    //                       })->orWhereHas('categoryTranslation', function($query) use ($name) {
    //                         $query->where('name', 'LIKE', '%'.$name.'%');
    //                     })->orWhereHas('sectorTranslation', function($query) use ($name) {
    //                         $query->where('name', 'LIKE', '%'.$name.'%');
    //                     })->orwhere('description', 'LIKE', '%'.$name.'%')
    //                       ->orWhere('category_id', $categoryId)
    //                       ->orWhere('sector_id', $sectorId);
    //                 });

    //     $jobs = $jobs->get();

    //     return $jobs;
    // }

    public function getJobsBySubscribtionData($data)
    {
    	$country = $this->country->where('name', $data['country'])->first();
    	$distance = config('app.search_distance');

		$search = $this->company->where('companies.country_id', $country->id)->where(function($query){
			$query->whereNull('companies.restrict')
				  ->orwhere('companies.restrict', '!=', 'true'); 	
		})->where('companies.type', 'subsidiary')->where('companies.sub_type', 'country_subsidiary')->with('generic')->with('sector');

		$name = $data['keyword'];
        $categoryId = $data['category_id'];
        $sectorId = $data['sector_id'];

        if($categoryId) {
        	$search = $search->where('category_id', $categoryId);
        }

        if($sectorId) {
        	$search = $search->where('sector_id', $sectorId);
        }

        if($name) {
        	$search = $search->where(function($query) use ($name) {
									$query->where(function($query) use ($name){
										$query->where('name', 'LIKE', '%'.$name.'%')
											    ->orWherehas('jobs', function($query) use ($name) {
											  		$query->where('name', 'LIKE', '%'.$name.'%');
											  	});
											  	})->orWhereHas('categoryTranslation', function($query) use ($name) {
													$query->where('name', 'LIKE', '%'.$name.'%');
												})->orWhereHas('sectorTranslation', function($query) use ($name) {
													$query->where('name', 'LIKE', '%'.$name.'%');
												})->orwhere('description', 'LIKE', '%'.$name.'%');
								});
        }

        // $search = $search->whereHas('jobs', function($query) use ($name) {
        // 	$query->where('is_sent', false);
        // });

		$search->leftJoin('company_cities', 'companies.id', '=', 'company_cities.company_id');
		$companies = $search->select(['companies.*', 'company_cities.latitude', 'company_cities.longitude', \DB::raw("( 6371 * acos( cos( radians(".$data['latitude'].") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(".$data['longtitude'].") ) + sin( radians(".$data['latitude'].") ) * sin( radians( latitude ) ) ) ) AS distance")])->having('distance', '<', $distance)->get();
		return $companies;


    }

    public function checkIfSent($jobId, $email)
    {
    	return $this->emailjob->where('job_id', $jobId)->where('email', $email)->first();
    }

    public function setJobUnsent($jobId)
    {
    	return $this->job->where('id', $jobId)->update(['is_sent' => false]);
    }

    public function setJobSent($data)
    {
    	return $this->emailjob->create($data);
    }

    public function getAllJobsTodayCount()
    {
    	return $this->job->whereDate('created_at', DB::raw('CURDATE()'))->count();
    }
}