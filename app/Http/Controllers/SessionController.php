<?php

namespace App\Http\Controllers;

use App\Http\Requests\Session\RegisterUserRequest;
use App\Mail\VerificationMail;
use App\Models\Email;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class SessionController extends Controller
{
	public function register(RegisterUserRequest $request)
	{
		$formFields = $request->validated();

		$formFields['password'] = bcrypt($formFields['password']);

		$userData = $request->except(['email']);
		$email = $request->email;

		$user = User::create($userData);
		if ($user)
		{
			$userEmail = Email::create([
				'user_id' => $user->id,
				'email'   => $email,
			]);
		}

		$response = [
			'user'  => $user,
			'email' => $userEmail,
		];

		$url = URL::temporarySignedRoute('verification.verify', now()->addMinutes(30), ['id' => $user->id, 'token' => hash('sha256', $email)]);

		Mail::to($email)->send(new VerificationMail(['url'=> $url, 'token' => hash('sha256', $email), 'id' => $user->id, 'user' => $user->name]));

		return response($response, 201);
	}

	public function verify(Request $request)
	{
		if ($request->hasValidSignature())
		{
			$request->hasValidSignature();
			return response('Verified', 200);
		}
		return response('Route expired');
	}
}
