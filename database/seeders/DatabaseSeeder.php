<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	public function run()
	{
		$tags = config('tag')['tags'];

		foreach ($tags as $tag)
		{
			Tag::create(['name' =>  $tag]);
		}
	}
}
