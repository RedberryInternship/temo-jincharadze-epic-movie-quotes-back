<?php

namespace App\Http\Controllers;

use App\Http\Requests\Quote\QuoteStoreRequest;
use App\Models\Movie;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;

class NewsFeedController extends Controller
{
	public function index(): JsonResponse
	{
		app()->setLocale(request('locale'));

		$quotes = Quote::with(['movie' => function ($query) {
			$query->with('user');
		}, 'comments' => function ($query) {
			$query->with('user');
		}, 'likes'])->filter(request(['search']))->orderBy('created_at', 'desc')->paginate(3);

		return response()->json($quotes, 200);
	}

	public function movies(): JsonResponse
	{
		$movies = Movie::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->select('id', 'name')->get();
		return response()->json($movies, 200);
	}

	public function store(QuoteStoreRequest $request): JsonResponse
	{
		$validated = $request->validated();

		$movieId = request('movie_id');

		if (!$validated && !$movieId)
		{
			return response()->json('', 422);
		}

		$quote = new Quote();

		$textTranslation = ['en' => ucwords($validated['quoteEn']), 'ka' => ucwords($validated['quoteKa'])];

		$image = $request->validated('image');

		if (request()->hasFile('image'))
		{
			$image = request()->file('image')->store('movie/images');
			$movieImg = asset('storage/' . $image);
		}

		$quote->setTranslations('text', $textTranslation);
		$quote->setAttribute('movie_id', $validated['movie_id']);
		$quote->setAttribute('image', $movieImg);
		$quote->setAttribute('movie_id', (int)$movieId);

		$quote->save();

		return response()->json($quote, 201);
	}
}
