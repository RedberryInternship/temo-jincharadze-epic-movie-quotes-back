<?php

namespace App\Http\Controllers;

use App\Http\Requests\Like\LikeRequest;
use App\Models\Like;
use Illuminate\Http\JsonResponse;

class LikeController extends Controller
{
	public function store(LikeRequest $request, Like $like): JsonResponse
	{
		$validated = $request->validated();

		$checkIsLiked = $like->where('user_id', $validated['user_id'])->where('quote_id', $validated['quote_id'])->first();

		if ($checkIsLiked)
		{
			$checkIsLiked->delete();
			return response()->json('unliked', 200);
		}

		$like->create([
			'user_id'  => $validated['user_id'],
			'quote_id' => $validated['quote_id'],
		]);

		return response()->json('liked', 201);
	}
}
