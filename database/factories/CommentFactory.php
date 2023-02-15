<?php

namespace Database\Factories;

use App\Models\Quote;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
	public function definition()
	{
		$user = User::factory()->create();
		$quote = Quote::factory()->create();

		return [
			'user_id'  => $user->id,
			'quote_id' => $quote->id,
			'comment'  => $this->faker->paragraph(),
		];
	}
}
