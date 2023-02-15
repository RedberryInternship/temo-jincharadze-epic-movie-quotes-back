<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmailFactory extends Factory
{
	public function definition()
	{
		$user = User::factory()->create();

		return [
			'email'                 => $this->faker->unique()->safeEmail(),
			'email_verified_at'     => now(),
			'user_id'               => $user->id,
			'primary'               => true,
		];
	}

	public function unverified()
	{
		return $this->state(fn (array $attributes) => [
			'email_verified_at' => null,
		]);
	}
}
