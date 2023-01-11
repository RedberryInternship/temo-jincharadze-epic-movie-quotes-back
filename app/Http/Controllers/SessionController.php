<?php

namespace App\Http\Controllers;

use App\Http\Requests\Session\AuthRequest;
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
	public function login(AuthRequest $request)
	{
		$login = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
		$remember = request()->has('remember') ? true : false;
		$request->merge([$login => $request->input('login')]);

		if ($login === 'email')
		{
			$email = Email::where('email', $request->login)->first();

			if ($email)
			{
				if (!$email->email_verified_at)
				{
					return response(['message' => 'Your email is not verified.'], 422);
				}
				$user = $email->user;
			}
			else
			{
				return response(['message' => 'Email not found!'], 400);
			}
		}

		if ($login === 'name')
		{
			$user = User::where('name', $request->login)->first();

			if (!$user)
			{
				return response(['message' => 'Username not found!'], 400);
			}

			if ($user)
			{
				$checkEmail = $user->email->where('email_verified_at', '!=', 'null')->first();
				if (!$checkEmail->email_verified_at)
				{
					return response(['message' => 'Your account email is not verified'], 422);
				}
			}
		}

		if (auth()->validate(['id' => $user->id, 'password' => $request->password]))
		{
			request()->session()->regenerate();
			auth()->loginUsingId($user->id, $remember);
			return response(['user' => auth()->user()], 200);
		}

		return response(['message' => 'Invalid Credentials'], 401);
	}

	public function register(RegisterUserRequest $request)
	{
		app()->setLocale($request->lang);
		$formFields = $request->validated();

		if (!$formFields)
		{
			return response('', 422);
		}

		$userData = $request->except(['email']);
		$userData['password'] = bcrypt($userData['password']);
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
				'email' => $userEmail->id,
			]
		);
		$frontUrl = Config('app.front_url') . app()->getLocale() . '/verify/' . '?verify=' . $url;

		Mail::to($email)->send(new VerificationMail(['url'=> $frontUrl, 'user' => $user->name]));

		return response($response, 201);
	}

	public function verify(Request $request)
	{
		$email = Email::where('id', $request->email)->first();

		if ($request->hasValidSignature())
		{
			if ($email->email_verified_at)
			{
				return response('Email is already verified');
			}
			$email->email_verified_at = Carbon::now();
			$email->save();
			return response('Verified', 200);
		}
		else
		{
			return response('Route expired', 403);
		}
	}
}
