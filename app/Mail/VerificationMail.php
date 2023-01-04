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

	public $id;

	public $user;

	public function __construct($data)
	{
		$this->url = $data['url'];
		$this->token = $data['token'];
		$this->id = $data['id'];
		$this->user = $data['user'];
	}

	public function envelope()
	{
		return new Envelope(
			subject: 'Please verify your email address',
		);
	}

	public function content()
	{
		return new Content(
			view: 'email.emailVerification',
		);
	}
}
