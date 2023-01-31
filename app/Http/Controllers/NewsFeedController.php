<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use Illuminate\Http\JsonResponse;

class NewsFeedController extends Controller
{
	public function index(): JsonResponse
	{
		$quotes = Quote::with(['movie' => function ($query) {
			$query->with('user');
		}, 'comments' => function ($query) {
			$query->with('user');
		}, 'likes'])->orderBy('created_at', 'desc')->paginate(3);

		return response()->json($quotes, 200);
	}
}
