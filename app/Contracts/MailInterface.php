<?php 
namespace App\Contracts;

Interface MailInterface
{
	/**
	* Send to email
	*
	* @param string $email
	* @param array $data
	* @param string $template
	* @param string $subject
	*
	* @return bool
	*/
	public function send_email($email, $data, $template, $subject = null);
}