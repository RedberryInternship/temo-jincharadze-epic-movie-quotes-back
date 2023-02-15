<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Quote;
use Illuminate\Database\Eloquent\Factories\Factory;

class LikeFactory extends Factory
{
	public function definition()
	{
		$user = User::factory()->create();
		$quote = Quote::factory()->create();

		return [
			'user_id'  => $user->id,
			'quote_id' => $quote->id,
		];
	}
}
