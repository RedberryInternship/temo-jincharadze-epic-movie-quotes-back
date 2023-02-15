<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
	public function definition()
	{
		return [
			'name'              => fake()->name(),
			'image'             => asset('assets/images/default.jpg'),
			'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
			'remember_token'    => Str::random(10),
		];
	}
}
