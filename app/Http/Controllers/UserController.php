<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
	public function me(): JsonResponse
	{
		$user = User::where('id', auth()->user()->id)->select(['id', 'name', 'image'])->first();
		$email = $user->email()->select('email', 'primary', 'id', 'user_id')->get();

		return response()->json(['user' => $user, 'emails' => $email], 200);
	}
}
