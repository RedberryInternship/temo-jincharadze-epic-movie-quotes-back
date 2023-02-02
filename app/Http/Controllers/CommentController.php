<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comment\CommentStoreRequest;
use App\Models\Comment;
use App\Models\Movie;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
	public function store(CommentStoreRequest $request): JsonResponse
	{
		$validated = $request->validated();

		if (!$validated)
		{
			return response()->json('', 422);
		}

		$comment = Comment::create([
			'user_id'  => $validated['user_id'],
			'quote_id' => $validated['quote_id'],
			'comment'  => $validated['comment'],
		]);

		$movie = Movie::where('id', request('movie_id'))->first();

		if ($movie->user_id !== auth()->user()->id)
		{
			Notification::create([
				'user_id'     => $movie->user_id,
				'quote_id'    => $validated['quote_id'],
				'has_new'     => true,
				'has_comment' => true,
				'sender_id'   => $validated['user_id'],
			]);
		}

		return response()->json($comment, 201);
	}
}
