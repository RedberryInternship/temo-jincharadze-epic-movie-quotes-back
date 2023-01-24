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

		$nameTranslations = ['en' => ucwords($validated['nameEn']), 'ka' => ucwords($validated['nameKa'])];
		$directorTranslation = ['en' => ucwords($validated['directorEn']), 'ka' => ucwords($validated['directorKa'])];
		$descriptionTranslation = ['en' => $validated['descriptionEn'], 'ka' => $validated['descriptionKa']];

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
		return response()->json(['movie' => $movie, 'tags' => $tags], 201);
	}

	public function update(MovieStoreRequest $request, $id): JsonResponse
	{
		$validated = $request->validated();

		$movie = Movie::where('id', $id)->first();

		$nameTranslations = ['en' => ucwords($validated['nameEn']), 'ka' => ucwords($validated['nameKa'])];
		$directorTranslation = ['en' => ucwords($validated['directorEn']), 'ka' => ucwords($validated['directorKa'])];
		$descriptionTranslation = ['en' => $validated['descriptionEn'], 'ka' => $validated['descriptionKa']];

		$image = $request->validated('image');

		if (request()->hasFile('image'))
		{
			$data = request()->file('image')->store('movie/images');
			$image = asset('storage/' . $data);
		}

		$movie->replaceTranslations('name', $nameTranslations);
		$movie->replaceTranslations('director', $directorTranslation);
		$movie->replaceTranslations('description', $descriptionTranslation);

		$movie->update([
			'budget' => (float)$validated['budget'],
			'image'  => $image,
			'year'   => (int)$validated['year'],
		]);

		$movie->save();

		if ($movie)
		{
			MovieTag::where('movie_id', $id)->delete();
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

	public function destroy($id): JsonResponse
	{
		$movie = Movie::where('id', $id)->first();
		$movie->delete();
		return response()->json('successfully deleted', 200);
	}

	public function userMovies(): JsonResponse
	{
		app()->setLocale(request('locale'));
		$user = auth()->user();
		$movie = Movie::where('user_id', $user->id)->filter(request(['search']))->orderBy('created_at', 'desc')->withCount('quotes')->get();
		return response()->json($movie, 200);
	}

	public function userMovie($id): JsonResponse
	{
		$movie = Movie::where('id', $id)->with(['tag', 'quotes' => function ($query) {
			$query->orderBy('created_at', 'desc');
		}])->get();

		return response()->json($movie, 200);
	}
}
