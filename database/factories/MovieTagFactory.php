<?php

namespace Database\Factories;

use App\Models\Movie;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

class MovieTagFactory extends Factory
{
	public function definition()
	{
		$tag = Tag::factory()->create(['name' => ['en' => 'Historical', 'ka' => 'ისტორიული']]);
		$movie = Movie::factory()->create();

		return [
			'tag_id'   => $tag->id,
			'movie_id' => $movie->id,
		];
	}
}
