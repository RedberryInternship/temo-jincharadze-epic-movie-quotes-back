<?php

namespace App\Http\Controllers;

use App\Http\Requests\Quote\QuoteStoreRequest;
use App\Http\Requests\Quote\QuoteUpdateRequest;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;

class QuoteController extends Controller
{
	public function store(QuoteStoreRequest $request): JsonResponse
	{
		$validated = $request->validated();

		if (!$validated)
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

		$quote->save();

		return response()->json($quote, 201);
	}

	public function destroy($id): JsonResponse
	{
		$quote = Quote::where('id', $id)->first();
		$quote->delete();
		return response()->json('successfully deleted', 200);
	}

	public function get(): JsonResponse
	{
		$quote = Quote::where('id', request('id'))->first();
		return response()->json($quote, 200);
	}

	public function update(QuoteUpdateRequest $request, $id): JsonResponse
	{
		$validated = $request->validated();

		$quote = Quote::where('id', $id)->first();

		$textTranslations = ['en' => ucwords($validated['quoteEn']), 'ka' => ucwords($validated['quoteKa'])];

		$image = $request->validated('image');

		if (request()->hasFile('image'))
		{
			$data = request()->file('image')->store('movie/images');
			$image = asset('storage/' . $data);
		}

		$quote->replaceTranslations('text', $textTranslations);

		$quote->update([
			'image' => $image,
		]);

		$quote->save();

		return response()->json('successfully updated', 200);
	}
}
