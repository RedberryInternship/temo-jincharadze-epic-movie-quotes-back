<?php

namespace App\Http\Controllers;

use App\Models\Email;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Query\Expression;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialRegisterController extends Controller
{
	public function redirectToProvider($locale, $type): JsonResponse
	{
		$locale === 'ka' ? $curLocale = 'ka' : $curLocale = '';
		$type === 'login' ? $curType = 'login' : $curType = 'register';

		return response()->json([
			'url'=> Socialite::driver('google')
				->stateless()
				->redirectUrl(config('services.google.redirect') . '/' . $curLocale . '?type=' . $curType . '&from=google')
				->redirect()
				->getTargetUrl(),
		]);
	}

	public function handleCallBack($locale, $type, Email $email): JsonResponse
	{
		$locale === 'ka' ? $curLocale = 'ka' : $curLocale = '';
		$type === 'register' ? $curType = 'register' : $curType = 'login';

		try
		{
			config([
				'services.google.redirect' => config('services.google.redirect') . '/' . $curLocale . '?type=' . $curType . '&from=google',
			]);

			$user = Socialite::driver('google')->stateless()->user();
		}
		catch(Expression $error)
		{
			return response()->json(['message' => 'forbidden'], 403);
		}

		$checkIfExists = User::where('google_id', $user->id)->first();
		$checkEmail = Email::where('email', $user->email)->first();
		$lowerCaseName = Str::lower($user->name);
		$userName = str_replace(' ', '', $lowerCaseName);

		if ($checkEmail && $checkEmail->user->password !== null)
		{
			return response()->json(['message' => 'exists'], 422);
		}

		if ($checkIfExists)
		{
			Auth::loginUsingId($checkIfExists->id, false);
			request()->session()->regenerate();
			return response()->json($checkIfExists, 200);
		}

		$newAccount = User::create([
			'name'      => $userName,
			'google_id' => $user->id,
			'image'     => $user->avatar,
		]);

		$newEmail = $email->create([
			'email'             => $user->email,
			'user_id'           => $newAccount->id,
			'email_verified_at' => Carbon::now(),
			'primary'           => true,
		]);

		Auth::loginUsingId($newAccount->id, false);
		request()->session()->regenerate();
		return response()->json([$newAccount, $newEmail], 201);
	}
}
