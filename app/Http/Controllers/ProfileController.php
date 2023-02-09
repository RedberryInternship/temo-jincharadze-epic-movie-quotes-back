<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\CheckEmailRequest;
use App\Http\Requests\Profile\GoogleProfileRequest;
use App\Http\Requests\Profile\PasswordChangeRequest;
use App\Http\Requests\Profile\UsernameStoreRequest;
use App\Mail\VerificationMail;
use App\Models\Email;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class ProfileController extends Controller
{
	public function checkUser(): JsonResponse
	{
		if (!request('username'))
		{
			return response()->json('username required', 422);
		}

		$user = request('userId');

		$checkUsername = User::where('name', request('username'))->where('id', '!=', $user)->first();

		if ($checkUsername)
		{
			return response()->json(['message' => 'username is already taken'], 422);
		}
		return response()->json('', 200);
	}

	public function update(GoogleProfileRequest $request): JsonResponse
	{
		$validated = $request->validated();
		if (!$validated)
		{
			return response()->json(['message' => 'required'], 422);
		}

		$username = $request->validated('username');
		$user = $request->validated('userId');

		$checkUsername = User::where('name', $username)->where('id', '!==', $user)->first();
		$authUser = User::where('id', $user)->first();

		if ($checkUsername)
		{
			return response()->json(['message' => 'username is already taken'], 422);
		}

		$image = $request->validated('image');

		if (request()->hasFile('image'))
		{
			$data = request()->file('image')->store('movie/images');
			$image = asset('storage/' . $data);
		}

		$authUser->update([
			'name'  => $username,
			'image' => $image,
		]);

		if (request('password'))
		{
			$authUser->update([
				'password' => bcrypt(request('password')),
			]);
		}

		return response()->json(['message' => 'user profile successfully updated!', 200]);
	}

	public function store(CheckEmailRequest $request): JsonResponse
	{
		$validated = $request->validated();

		if (!$validated)
		{
			return response()->json('', 422);
		}

		$email = Email::create([
			'email'   => $validated['email'],
			'user_id' => auth()->user()->id,
			'primary' => false,
		]);

		$user = User::where('id', auth()->user()->id)->first();

		$url = URL::temporarySignedRoute(
			'verify.email',
			now()->addMinutes(30),
			[
				'email' => $email->id,
			]
		);
		$frontUrl = Config('app.front_url') . app()->getLocale() . '/email-verify/' . '?verify=' . $url;

		Mail::to($validated['email'])->send(new VerificationMail(['url'=> $frontUrl, 'user' => $user->name]));

		return response()->json($email, 201);
	}

	public function verifyEmail(Request $request)
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

	public function checkEmail(CheckEmailRequest $request): JsonResponse
	{
		$validated = $request->validated();

		if (!$validated)
		{
			return response()->json('', 422);
		}
		return response()->json('', 200);
	}

	public function delete(): JsonResponse
	{
		$email = Email::where('email', request('email'))->first();

		$email->delete();

		return response()->json('email successfully deleted', 200);
	}

	public function updatePrimary(): JsonResponse
	{
		$userEmails = Email::where('user_id', auth()->user()->id)->get();

		foreach ($userEmails as $email)
		{
			if ($email->primary == true)
			{
				$email->update([
					'primary' => false,
				]);
			}
		}

		$curEmail = $userEmails->where('email', request('email'))->where('email_verified_at', '!=', null)->first();

		$curEmail->update([
			'primary' => true,
		]);

		return response()->json('successfully updated to primary', 200);
	}

	public function updateName(UsernameStoreRequest $request): JsonResponse
	{
		$validated = $request->validated();

		if (!$validated)
		{
			return response()->json('', 422);
		}

		$checkUsername = User::where('name', $validated['username'])->where('id', '!==', auth()->user()->id)->first();

		if ($checkUsername)
		{
			return response()->json(['message' => 'username is already taken'], 422);
		}

		$authUser = User::where('id', auth()->user()->id)->first();

		$authUser->update([
			'name' => $validated['username'],
		]);

		return response()->json('username updated successfully', 200);
	}

	public function updatePassword(PasswordChangeRequest $request)
	{
		$validated = $request->validated();
		if (!$validated)
		{
			return response()->json('', 422);
		}
		$authUser = User::where('id', auth()->user()->id)->first();

		$authUser->update([
			'password' => bcrypt($validated['password']),
		]);

		return response()->json('password updated successfully', 200);
	}
}
