<?php

namespace App\Http\Controllers;

use App\Http\Requests\Password\EmailRequest;
use App\Http\Requests\Password\PasswordResetRequest;
use App\Mail\PasswordResetMail;
use App\Models\Email;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class PasswordController extends Controller
{
	public function send(EmailRequest $request)
	{
		app()->setLocale($request->lang);

		$check = $request->validated();

		$checkEmail = Email::where('email', $check['email'])->first();
		$checkUser = User::where('id', $checkEmail->user_id)->first();

		if ($checkUser->google_id !== null)
		{
			return response()->json('Cannot reset password on google email', 422);
		}

		if (!$check)
		{
			return response('', 422);
		}

		$email = Email::where('email', $request->email)->first();

		if (!$email->email_verified_at)
		{
			return response(['message' => 'Your account email is not verified'], 422);
		}

		$user = User::where('id', $email->user_id)->first();

		$response = [
			'user' => $user->name,
		];

		$url = URL::temporarySignedRoute('check', now()->addMinutes(30), [
			'email' => $email->id,
		]);

		$frontUrl = Config('app.front_url') . app()->getLocale() . '/password-reset/' . '?verify=' . $url;
		Mail::to($request->email)->send(new PasswordResetMail(['url'=> $frontUrl, 'user' => $user->name]));
		return response($response, 200);
	}

	public function check()
	{
		if (request()->hasValidSignature())
		{
			return response(['message' => 'Valid token', 'email' => request()->email], 200);
		}
		else
		{
			return response('Token expired', 403);
		}
	}

	public function update(PasswordResetRequest $request)
	{
		$validate_password = $request->validated();
		$confirm_password = request()->input('confirm_password');

		$user = Email::where('id', request()->email)->first();

		if ($validate_password['password'] == $confirm_password)
		{
			User::where('id', $user->user_id)->update(['password' => bcrypt($validate_password['password'])]);
			return response('updated successfully', 201);
		}
		return response('password doesnt match', 401);
	}
}
