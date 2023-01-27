<?php

namespace App\Http\Controllers;

use App\Http\Requests\Session\AuthRequest;
use App\Http\Requests\Session\RegisterUserRequest;
use App\Mail\VerificationMail;
use App\Models\Email;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class SessionController extends Controller
{
	public function login(AuthRequest $request): JsonResponse
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
					return response()->json(['message' => 'Your email is not verified.'], 422);
				}
				$user = $email->user;
			}
			else
			{
				return response()->json(['message' => 'Email not found!'], 400);
			}
		}

		if ($login === 'name')
		{
			$user = User::where('name', $request->login)->first();

			if (!$user)
			{
				return response()->json(['message' => 'Username not found!'], 400);
			}

			if ($user)
			{
				$checkEmail = $user->email->where('email_verified_at', '!=', 'null')->first();
				if (!$checkEmail->email_verified_at)
				{
					return response()->json(['message' => 'Your account email is not verified'], 422);
				}
			}
		}

		if (auth()->validate(['id' => $user->id, 'password' => $request->password]))
		{
			auth()->loginUsingId($user->id, $remember);
			request()->session()->regenerate();
			return response()->json(['user' => auth()->user()], 200);
		}

		return response()->json(['message' => 'Invalid Credentials'], 401);
	}

	public function logout(): JsonResponse
	{
		request()->session()->invalidate();
		request()->session()->regenerateToken();
		return response()->json('Logged out');
	}

	public function register(RegisterUserRequest $request): JsonResponse
	{
		app()->setLocale($request->lang);
		$formFields = $request->validated();

		if (!$formFields)
		{
			return response()->json('', 422);
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
				'primary' => true,
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

		return response()->json($response, 201);
	}

	public function verify(Request $request): JsonResponse
	{
		$email = Email::where('id', $request->email)->first();

		if ($request->hasValidSignature())
		{
			if ($email->email_verified_at)
			{
				return response()->json('Email is already verified');
			}
			$email->email_verified_at = Carbon::now();
			$email->save();
			return response()->json('Verified', 200);
		}
		else
		{
			return response()->json('Route expired', 403);
		}
	}
}
