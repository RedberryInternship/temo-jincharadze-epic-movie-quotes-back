<?php

namespace App\Http\Controllers;

use App\Http\Requests\Movie\MovieStoreRequest;
use App\Models\Movie;
use App\Models\MovieTag;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;

class MovieController extends Controller
{
	public function tags(): JsonResponse
	{
		$getAllTags = Tag::select('id', 'name')->get();

		return response()->json($getAllTags, 200);
	}

	public function store(MovieStoreRequest $request): JsonResponse
	{
		$validated = $request->validated();
		$movie = new Movie();

		$nameTranslations = [['en' => $validated['nameEn'], 'ka' => $validated['nameKa']]];
		$directorTranslation = [['en' => $validated['directorEn'], 'ka' => $validated['directorKa']]];
		$descriptionTranslation = [['en' => $validated['descriptionEn'], 'ka' => $validated['descriptionKa']]];

		$image = $request->validated('image');

		if (request()->hasFile('image'))
		{
			$image = request()->file('image')->store('movie/images');
			$movieImg = asset('storage/' . $image);
		}

		$movie->setTranslations('name', $nameTranslations);
		$movie->setTranslations('director', $directorTranslation);
		$movie->setTranslations('description', $descriptionTranslation);
		$movie->setAttribute('user_id', $validated['user_id']);
		$movie->setAttribute('budget', (float)$validated['budget']);
		$movie->setAttribute('year', (int)$validated['year']);
		$movie->setAttribute('image', $movieImg);

		$movie->save();

		if ($movie)
		{
			$tags = json_decode($validated['tags']);
			foreach ($tags as $tag)
			{
				MovieTag::create([
					'movie_id' => $movie->id,
					'tag_id'   => (int)$tag,
				]);
			}
		}
		return response()->json(['movie' => $movie, 'tags' => $tags], 200);
	}

	public function userMovies(): JsonResponse
	{
		$user = auth()->user();
		$movie = $user->movie()->select(['id', 'name', 'image', 'year'])->get();
		return response()->json($movie, 200);
	}
}
