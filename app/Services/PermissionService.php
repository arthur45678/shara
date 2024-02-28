<?php

namespace App\Services;

use App\Contracts\PermissionInterface;
use Cartalyst\Sentinel\Roles\EloquentRole as Role;
use \App\Permission;

class PermissionService implements PermissionInterface
{

	/**
	 * Object of Permission class.
	 *
	 * @var $role 
	 */
	private $permission;

	/**
	 * Create a new instance of PermissionService class.
	 *
	 * @return void
	 */

	public function __construct()
	{
		$this->permission = new Permission(); 
	}

	/**
	 * get all roles
	 *
	 * @return collection
	 */
	public function getAllPermissions()
	{
		return $this->permission->all();
	}
}