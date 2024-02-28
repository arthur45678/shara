<?php

namespace App\Services;

use App\Contracts\SubscribtionInterface;
use Illuminate\Support\Facades\DB;
use App\Subscribtion;
use App\Notification;

class SubscribtionService implements SubscribtionInterface 
{

	/**
	 * Object of Sector class.
	 *
	 * @var $sector 
	 */
	private $sector;

	/**
	 * Create a new instance of SectorService class.
	 *
	 * @return void
	 */

	public function __construct()
	{
		$this->subscribtion = new Subscribtion();
		$this->notification = new Notification();
	}

	/**
	 * get all subscribtions
	 */
	public function getSubscribtions()
	{
		return 	$this->subscribtion->all();
	}

		/**
	 * get all subscribtions
	 */
	public function getSubscribtionsPaginated()
	{
		return 	$this->subscribtion->orderBy('created_at', 'desc')->paginate(50);
	}

	/**
	 * create subscribtion
	 */
	public function subscribeForJob($data)
	{
		$status = [];
		$job = $this->subscribtion->where('email', $data['email'])->where('keyword', $data['keyword'])->where('country', $data['country'])->where('city', $data['city'])->where('category_id', $data['category_id'])->where('sector_id', $data['sector_id'])->first();
		if($job){
			$status['existsMessage'] = ['You are already activated for these jobs.'];
		}else{
			$subscribtion = $this->subscribtion->create($data);
			$status['object'] = $subscribtion;
			$status['successMessage'] = ['Your email alerts have been activated.'];
		}
		return 	$status;
	}

	/**
	 * get jobs for notification
	 */
	public function getJobsForNotification()
	{
		return	$this->notification->all();
	}

	/**
	 * get notification by job id and user email
	 */
	public function getNotification($jobId, $userEmail)
	{
		return $this->notification->where('job_id', $jobId)->where('user_email', $userEmail)->first();
	}

	/**
	 * get notification by job id and user email
	 */
	public function createNotification($data)
	{
		return $this->notification->create($data);
	}

	/**
	 * update notification
	 */
	public function updateNotification($id, $data)
	{
		return $this->notification->where('id', $id)->update($data);
	}

	/**
	 * remove subscibtion
	 */
	public function removeSubscribtion($id)
	{
		$subscribtion = $this->subscribtion->where('id', $id)->first();
		if($subscribtion){
			$subscribtion->delete();
		}
	}

	/**
	 * get subscibtion by Id
	 */
	public function getSubscibtionById($id)
	{
		return $this->subscribtion->where('id', $id)->first();
	}

	/**
	 * remove notification
	 */
	public function removeNotification($id)
	{
		return $this->notification->find($id)->delete();
	}

	/**
	 * get subscribtion by subscribtion data
	 */
	public function getSubscribtion($data)
	{
		$subscribtion = $this->subscribtion->where('email', $data['email'])->where('keyword', $data['keyword'])->where('latitude', $data['latitude'])->where('longtitude', $data['longtitude'])->where('category_id', $data['category_id'])->where('sector_id', $data['sector_id'])->where('country', $data['country'])->where('city', $data['city'])->first();
		return $subscribtion;
	}

	public function getSubscriptionsTodayCount()
	{
		return $this->subscribtion->whereDate('created_at', DB::raw('CURDATE()'))->count();
	}
}