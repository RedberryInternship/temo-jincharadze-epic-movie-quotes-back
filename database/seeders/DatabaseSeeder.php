<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Database\Factories\TagFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	public function run()
	{
		$tags = config('tag')['tags'];

		foreach ($tags as $tag)
		{
			TagFactory::factory()->create(['name' =>  $tag]);
		}
	}
}
