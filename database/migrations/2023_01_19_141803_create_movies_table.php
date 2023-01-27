<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up()
	{
		Schema::create('movies', function (Blueprint $table) {
			$table->id();
			$table->foreignId('user_id')->references('id')->on('users')->constrained()->cascadeOnDelete();
			$table->text('name');
			$table->string('director');
			$table->text('description');
			$table->bigInteger('budget');
			$table->integer('year');
			$table->string('image');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::dropIfExists('movies');
	}
};
