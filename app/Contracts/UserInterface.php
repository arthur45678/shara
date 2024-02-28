<?php

namespace App\Contracts;

interface UserInterface
{
	/**
	 * get all users
	 *
	 * @return array
	 */
	public function getAllUsers();

	/**
	 * get a user by email
	 *
	 * @param string $email
	 * @return object
	 */
	public function getUserByEmail($email);

	/**
	 * get a user by token
	 *
	 * @param string $token
	 * @return object
	 */
	public function getUserByToken($token);
}