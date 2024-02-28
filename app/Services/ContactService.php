<?php

namespace App\Services;

use App\Contracts\ContactInterface;
use App\Contact;

class ContactService implements ContactInterface
{
	/**
	 * Object of Company class.
	 *
	 * @var $company 
	 */
	private $company;

	/**
	 * Create a new instance of ContactService class.
	 *
	 * @return void
	 */

	public function __construct()
	{
		$this->contact = new Contact(); 
	}

	/**
	 * create contact
	 */
	public function createContact($data)
	{
		return $this->contact->create($data);
	}

	/**
	 * get all contacts
	 */
	public function getAll()
	{
		return $this->contact->orderBy('created_at', 'asc')->paginate(50);
	}

	/**
	 * delete contact
	 */
	public function deleteContact($id)
	{
		return 	$this->contact->find($id)->delete();
	}

}