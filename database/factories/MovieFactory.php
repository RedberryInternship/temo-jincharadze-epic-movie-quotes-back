<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MovieFactory extends Factory
{
	public function definition()
	{
		$user = User::factory()->create();

		return [
			'user_id'         => $user->id,
			['name' => [
				'en' => $this->faker->sentence(), 'ka' => $this->faker->sentence(),
			],
			],
			['director' => [
				'en' => $this->faker->name(), 'ka' => $this->faker->name(),
			],
			],
			['description' => [
				'en' => $this->faker->paragraph(), 'ka' => $this->faker->paragraph(),
			],
			],
			'budget'          => $this->faker->randomNumber(),
			'year'            => $this->faker->year(),
			'image'           => 'https://source.unsplash.com/random',
		];
	}
}
