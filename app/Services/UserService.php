<?php

namespace App\Services;

use App\Contracts\UserInterface;
use App\User;
use App\Downloads;
use App\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService implements UserInterface
{

	/**
	 * Object of User class.
	 *
	 * @var $user 
	 */
	private $user;

	/**
	 * Create a new instance of UserService class.
	 *
	 * @return void
	 */

	public function __construct()
	{
		$this->user = new User();
		$this->downloads = new Downloads(); 
		$this->category = new Category(); 
	}

	/**
	 * get all users
	 *
	 * @return array
	 */
	public function getAllUsers()
	{
		return $this->user->with('roles')->get();
	}

	public function getAllUsersPaginate()
	{
		return $this->user->with('roles')->paginate(50);
	}

	/**
	 * get a user by email
	 *
	 * @param string $email
	 * @return object
	 */
	public function getUserByEmail($email)
	{
		return $this->user->where('email', $email)->first();
	}
	
	/**
	 * get a user by email
	 *
	 * @param string $email
	 * @return object
	 */
	public function getUserByEmailAndToken($email, $token)
	{
		return $this->user->where('email', $email)->where('password_reset_token', $token)->first();
	}
	
	/**
	 * get a user by token
	 *
	 * @param string $token
	 * @return object
	 */
	public function getUserByToken($token)
	{
		return $this->user->where('verify_token', $token)->first();
	}

	public function getUserByFacebookId($id)
	{
		return $this->user->where('facebook_id', $id)->first();
	}

	public function editUser($object, $data)
	{
		return $object->update($data);
	}

	public function getUserById($id)
	{
		return $this->user->where('id', $id)->with('languages', 'skills', 'roles', 'applications')->first();
	}

	public function generateToken($userId, $token)
	{
		$userToken = $this->downloads->where('user_id', $userId)->first();
		if($userToken){
			$token = $this->downloads->where('user_id', $userId)->update(['token' => $token]);
		}else{
			$token = $this->downloads->create(['user_id' => $userId, 'token' => $token]);
		}

		return $token ;
	}

	/**
	 * get user token fod download
	 */
	public function getUserByDownloaToken($token)
	{
		return $this->downloads->where('token', $token)->first();
	}

	/**
	 * get registered users
	 */
	public function getRegisteredUsers()
	{
		$users = $this->user->where('role', 'from_registration')->orderBy('id','desc')->paginate(50);
		return $users;
	}

	/**
	 * get users created from admin
	 */
	public function getUsersFromAdmin()
	{
		$users = $this->user->where('role', 'from_admin')->paginate(50);
		return $users;
	}

	/**
	 * search users
	 */
	public function searchUsers($searchDetails, $sort, $type)
	{
		$users = $this->user->where('role', 'from_registration')->with('skills');

		$email = $searchDetails['email'];
		$country = $searchDetails['country'];
		if(isset($searchDetails['city'])) {
			$city = $searchDetails['city'];
		}
		$skill = $searchDetails['skill'];
		$transport = $searchDetails['transport'];
		$education = $searchDetails['education'];
		$schedule = $searchDetails['schedule'];
		$days = $searchDetails['week_days'];
		$hours = $searchDetails['hours'];
		$workingArea = $searchDetails['working_area'];
		$fromDate = $searchDetails['fromDate'];
		$toDate = $searchDetails['toDate'];


		if($transport){
			// $users = $users->where('transport', 'LIKE'	, '%'.$transport.'%');
			$users = $users->whereRaw('json_contains(transport, \'["'.$transport.'"]\')');
		}


		if($email){
			$users = $users->where('email', $email);
		}

		if($country){
			$users = $users->where('country', $country);
		}

		if(isset($city) && $city != ''){
			$users = $users->where('city', $city);
		}

		if($skill){
			$users = $users->whereHas('skills', function ($query) use ($skill)  {
			    $query->where('category_id',  $skill);
			});
		}
		// if($transport){
		// 	dd(1245);
		// 	// $users = $users->where('transport', 'LIKE'	, '%'.$transport.'%');
		// 	$users = $users->whereRaw('json_contains(transport, \'["'.$transport.'"]\')');
		// }

		if($education){
			// $users = $users->where('education', 'LIKE'	, '%'.$education.'%');
			$users = $users->whereRaw('json_contains(education, \'["'.$education.'"]\')');
		}

		if($schedule){
			$users = $users->where('schedule', $schedule);
		}

		if($days){
			// $users = $users->where('week_days', 'LIKE'	, '%'.$days.'%');
			$users = $users->whereRaw('json_contains(week_days, \'["'.$days.'"]\')');

		}

		if($hours){
			// $users = $users->where('hours', 'LIKE'	, '%'.$hours.'%');
			$users = $users->whereRaw('json_contains(hours, \'["'.$hours.'"]\')');
		}

		if($workingArea){
			// $users = $this->user->where('working_area', 'LIKE'	, '%'.$workingArea.'%');
			$users = $users->whereRaw('json_contains(working_area, \'["'.$workingArea.'"]\')');
		}

		if($fromDate || $toDate) {
			if($fromDate) {
				$users = $users->whereDate('created_at', '>=', $fromDate);
			}

			if($toDate) {
				$users = $users->whereDate('created_at', '<=', $toDate);
			}
			
		}

		$users = $users->orderBy($sort, $type);
		

		return $users->paginate(50);

	}

	/**
	 * change user restirction
	 */
	public function changeRestriction($id)
	{
		$user = $this->user->where('id', $id)->first();
		if($user->admin_type == null){
			if($user->restrict == 'true')
				$updateData = ['restrict' => 'false'];
			else
				$updateData = ['restrict' => 'true'];

			return $user->update($updateData);
		}
	}

	public function checkIfUserAppliedCompany($user_id, $company_id)
	{
		return \DB::table('job_user')->where('job_user.user_id', $user_id)->where('job_user.company_id', $company_id)->whereNull('job_user.job_id')->first();
	}

	public function applyForCompany($user_id, $company_id)
	{
		return \DB::table('job_user')->insert([
				[
					'user_id' => $user_id,
					'company_id' => $company_id
				]
			]);
	}

	public function getAllApplicants($paramOrder, $order)
	{
		$perPage = 50;
		$applications = DB::select("select job_user.job_id as job_id, job_user.company_id, job_user.user_id, job_user.created_at, jobs.name as job_name, users.id as userId, users.first_name, users.last_name, users.email, users.country, users.city, companies.name as company_name, companies.id as company_id from job_user join jobs on job_user.job_id = jobs.id join companies on job_user.company_id = companies.id join users on job_user.user_id = users.id order by ".$paramOrder." ".$order);
		$count = count($applications);
		$applications = collect($applications);
		$currentPage = LengthAwarePaginator::resolveCurrentPage();
		$currentPageSearchResults = $applications->slice(($currentPage - 1) * $perPage, $perPage)->all();
		// $paginator = new \Illuminate\Pagination\Paginator($applications, $perPage);
		$paginator = new LengthAwarePaginator($currentPageSearchResults, count($applications), $perPage, $currentPage, ['path' => LengthAwarePaginator::resolveCurrentPath()]);
		//$paginator = $paginator->setPath($url)->render();
		$data = [
			'applications' => $paginator,
			'count' => $count
		];
		return $data;
		// return $this->user->whereHas('applications')->with('applications')->paginate(50);
	}

	public function getFilteredApplicants($filterDetails, $paramOrder, $order, $request)
	{
		$perPage = 50;

		$queryString = "select job_user.job_id as job_id, job_user.company_id, job_user.user_id, job_user.created_at, jobs.name as job_name, users.id as userId, users.first_name, users.last_name, users.email, users.country, users.city, companies.name as company_name, companies.id as company_id from job_user join jobs on job_user.job_id = jobs.id join companies on job_user.company_id = companies.id join users on job_user.user_id = users.id where jobs.id > 0";
		
		if(isset($filterDetails['company'])) {
			if($filterDetails['company']) {
				$queryString = $queryString." and job_user.company_id = ".$filterDetails['company']."";
			}
		}
		if(isset($filterDetails['job'])) {
			if($filterDetails['job']) {
				$queryString = $queryString." and job_user.job_id = ".$filterDetails['job']."";
			}
		}
		if(isset($filterDetails['category'])) {
			if($filterDetails['category']) {
				$queryString = $queryString." and jobs.category_id = ".$filterDetails['category']."";	
			}
		}
		if(isset($filterDetails['country'])) {
			if($filterDetails['country']) {
				$queryString = $queryString." and jobs.country_id = ".$filterDetails['country']."";
			}
		}
		$queryString = $queryString." order by ".$paramOrder." ".$order;
		$applications = DB::select($queryString);

		// $paginator = new \Illuminate\Pagination\Paginator($applications, $perPage);
		
		// $count = count($applications);
		$count = count($applications);
		$applications = collect($applications);
		$currentPage = LengthAwarePaginator::resolveCurrentPage();
		$currentPageSearchResults = $applications->slice(($currentPage - 1) * $perPage, $perPage)->all();
		// $paginator = new \Illuminate\Pagination\Paginator($applications, $perPage);
		$paginator = new LengthAwarePaginator($currentPageSearchResults, count($applications), $perPage, $currentPage, ['path' => LengthAwarePaginator::resolveCurrentPath(), 'query' => $request->query(),]);
		//$paginator = $paginator->setPath($url)->render();
		$data = [
			'applications' => $paginator,
			'count' => $count
		];
		return $data;
		
	}

	public function applicationsCount()
	{
		return DB::select("select count(*) as count from job_user");
	}

	public function applicationsCountToday()
	{
		return DB::select("select count(*) as count from job_user WHERE Date(created_at) = CURDATE()");
	}

	public function getUsers()
	{
		return $this->user->where('role', 'from_registration')->get();
	}

	public function getUsersTodayCount()
	{
		return $this->user->where('role', 'from_registration')->whereDate('created_at', DB::raw('CURDATE()'))->count();
	}

	public function getUserByUsername($username)
	{
		return $this->user->where('username', $username)->with('skills')->first();
	}

	public function getSuperAdmin()
	{
		return $this->user->where('id', 1)->first();
	}
}