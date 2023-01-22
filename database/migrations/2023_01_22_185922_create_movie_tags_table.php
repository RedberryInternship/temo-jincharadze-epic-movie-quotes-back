<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up()
	{
		Schema::create('movie_tags', function (Blueprint $table) {
			$table->id();
			$table->foreignId('tag_id')->references('id')->on('tags')->constrained()->cascadeOnDelete();
			$table->foreignId('movie_id')->references('id')->on('movies')->constrained()->cascadeOnDelete();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::dropIfExists('movie_tags');
	}
};
