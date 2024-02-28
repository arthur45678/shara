<?php

namespace App\Services;

use App\Contracts\MailInterface;
use Mail;

class MailService implements MailInterface 
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
	public function send_email($email, $data, $template, $subject = null)
	{
		//$administratorEmail = config('mail.administratorEmail');
		Mail::send("emails.".$template, $data, function($message) use($email, $subject)
		{
			// $message->from('mailgun@sandbox6281f396a4f347eaa5232812d2db408d.mailgun.org');
			$message->from('noreply@sharado.com');
			$message->to($email)->subject($subject);
		});
		
	}

	/**
	 * notify users for job
	 */
	public function notifyForJob($data)
	{
		Mail::send('emails.job_notification', $data, function($message) use ($data)
        {
            $message->from('mailgun@sandbox6281f396a4f347eaa5232812d2db408d.mailgun.org');
            $message->to($data['email'])->subject("Job Alert");
        });
	}
}