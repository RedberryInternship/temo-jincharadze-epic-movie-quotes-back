<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Quote;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
	public function definition()
	{
		$user = User::factory()->create();
		$quote = Quote::factory()->create();
		$sender = User::factory()->create();

		return [
			'user_id'     => $user->id,
			'quote_id'    => $quote->id,
			'has_new'     => true,
			'has_comment' => true,
			'sender_id'   => $sender->id,
		];
	}
}
