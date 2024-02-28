<?php

namespace App\Contracts;

interface RoleInterface
{
	/**
	 * get all roles
	 *
	 * @return collection
	 */
	public function getAllRoles();
}