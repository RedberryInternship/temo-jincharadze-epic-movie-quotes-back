<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
	public function me(): JsonResponse
	{
		$user = User::where('id', auth()->user()->id)->select(['id', 'name', 'image'])->with(['emails' => function ($query) {
			$query->select('email', 'primary', 'id', 'user_id');
		}])->first();

		return response()->json(['user' => $user], 200);
	}
}
