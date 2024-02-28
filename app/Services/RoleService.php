<?php

namespace App\Services;

use App\Contracts\RoleInterface;
use Cartalyst\Sentinel\Roles\EloquentRole as Role;

class RoleService implements RoleInterface
{

	/**
	 * Object of Role class.
	 *
	 * @var $role 
	 */
	private $role;

	/**
	 * Create a new instance of UserService class.
	 *
	 * @return void
	 */

	public function __construct()
	{
		$this->role = new Role(); 
	}

	/**
	 * get all roles
	 *
	 * @return collection
	 */
	public function getAllRoles()
	{
		return $this->role->all();
	}

	public function getAllRolesPaginate()
	{
		return $this->role->paginate(50);
	}

	/**
	 * return all users in role
	 */
	public function getUsersInRole($role)
	{
		return $role->users()->get();
	}
}