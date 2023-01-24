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

	public function movies()
	{
		return $this->belongsTo(Movie::class);
	}

	public function likes()
	{
		return $this->belongsTo(Like::class);
	}

	public function comments()
	{
		return $this->hasMany(Comment::class);
	}
}
