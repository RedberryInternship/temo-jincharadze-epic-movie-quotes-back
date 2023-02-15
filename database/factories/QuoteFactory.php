<?php

namespace Database\Factories;

use App\Models\Movie;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuoteFactory extends Factory
{
	public function definition()
	{
		$movie = Movie::factory()->create();

		return [
			'movie_id'         => $movie->id,
			['text' => [
				'en' => $this->faker->sentence(), 'ka' => $this->faker->sentence(),
			],
			],
			'image'           => 'https://source.unsplash.com/random',
		];
	}
}
