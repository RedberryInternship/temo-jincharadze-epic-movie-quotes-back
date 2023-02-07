<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\GoogleProfileRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

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
		if (!$request->validated())
		{
			return response()->json(['message' => 'required'], 422);
		}

		$username = $request->validated('username');
		$user = $request->validated('userId');

		$checkUsername = User::where('name', $username)->where('id', '!=', $user)->first();
		$gmailUser = User::where('id', $user)->first();

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

		$gmailUser->update([
			'name'  => $username,
			'image' => $image,
		]);

		return response()->json(['message' => 'user profile successfully updated!', 200]);
	}
}
