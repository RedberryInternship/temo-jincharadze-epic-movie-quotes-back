<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Quote extends Model
{
	use HasTranslations;

	use HasFactory;

	public $translatable = ['text'];

	protected $fillable = [
		'movie_id',
		'text',
		'image',
	];

	public function movie()
	{
		return $this->belongsTo(Movie::class);
	}
}
