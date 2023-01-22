<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
	public function me(): JsonResponse
	{
		$user = auth()->user();
		$user_data = $user->select(['id', 'name', 'image'])->first();
		$email = $user->email()->select('email', 'primary', 'id', 'user_id')->get();

		return response()->json(['user' => $user_data, 'emails' => $email], 200);
	}
}
