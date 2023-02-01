<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Movie;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Like\LikeRequest;

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

		$movie = Movie::where('id', request('movie_id'))->first();

		if ($movie->user_id !== auth()->user()->id)
		{
			Notification::create([
				'user_id'     => $movie->user_id,
				'quote_id'    => $validated['quote_id'],
				'has_new'     => 1,
				'sender_id'   => $validated['user_id'],
			]);
		}

		return response()->json('liked', 201);
	}
}
