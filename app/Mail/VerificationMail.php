<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerificationMail extends Mailable
{
	use Queueable, SerializesModels;

	public $url;

	public $token;

	public $user;

	public function __construct($data)
	{
		$this->url = $data['url'];
		$this->user = $data['user'];
	}

	public function envelope()
	{
		return new Envelope(
			subject: __('email.subject'),
		);
	}

	public function content()
	{
		return new Content(
			view: 'email.EmailVerification',
		);
	}
}
