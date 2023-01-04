<?php

namespace App\Http\Controllers;

use App\Http\Requests\Session\RegisterUserRequest;
use App\Mail\VerificationMail;
use App\Models\Email;
use App\Models\User;
use Carbon\Carbon;
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

		$url = URL::temporarySignedRoute(
			'verification.verify',
			now()->addMinutes(30),
			[
				'user' => $user->name,
			]
		);
		$frontUrl = Config('app.front_url') . '?verify-account=' . $url;
		Mail::to($email)->send(new VerificationMail(['url'=> $frontUrl, 'user' => $user->name, 'email' => $email]));

		return response($response, 201);
	}

	public function verify(Request $request)
	{
		$email = Email::where('email', $request->email)->first();

		if ($request->hasValidSignature())
		{
			if (!is_null($email->email_verified_at))
			{
				return response('Email is already verified');
			}
			$email->email_verified_at = Carbon::now();
			$email->save();
			return response('Verified', 200);
		}
		return response('Route expired');
	}
}
