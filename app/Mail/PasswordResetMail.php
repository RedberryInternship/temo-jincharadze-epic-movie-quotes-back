<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
	use Queueable, SerializesModels;

	public $url;

	public $user;

	public function __construct($data)
	{
		$this->url = $data['url'];
		$this->user = $data['user'];
	}

	public function envelope()
	{
		return new Envelope(
			subject: __('password.subject'),
		);
	}

	public function content()
	{
		return new Content(
			view: 'email.PasswordReset',
		);
	}
}
