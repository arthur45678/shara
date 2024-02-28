<?php

namespace App\Contracts;

interface PermissionInterface
{
	/**
	 * get all roles
	 *
	 * @return collection
	 */
	public function getAllPermissions();
}