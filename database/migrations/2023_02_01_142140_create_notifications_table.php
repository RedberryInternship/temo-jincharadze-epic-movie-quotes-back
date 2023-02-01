<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up()
	{
		Schema::create('notifications', function (Blueprint $table) {
			$table->id();
			$table->foreignId('user_id')->references('id')->on('users')->constrained()->cascadeOnDelete();
			$table->foreignId('quote_id')->references('id')->on('quotes')->constrained()->cascadeOnDelete();
			$table->boolean('has_new')->default(false);
			$table->boolean('has_comment')->default(false);
			$table->bigInteger('sender_id');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::dropIfExists('notifications');
	}
};
