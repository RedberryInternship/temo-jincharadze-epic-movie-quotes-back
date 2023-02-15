<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TagFactory extends Factory
{
	public function definition()
	{
		return [
			['name' => [
				'en' => $this->faker->sentence(), 'ka' => $this->faker->sentence(),
			]],
		];
	}
}
