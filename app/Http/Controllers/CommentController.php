<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comment\CommentStoreRequest;
use App\Models\Comment;
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

		return response()->json($comment, 201);
	}
}
