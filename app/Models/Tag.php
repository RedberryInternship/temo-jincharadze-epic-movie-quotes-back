<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Tag extends Model
{
	use HasTranslations;

	use HasFactory;

	protected $fillable = [
		'name',
	];

	public $translatable = ['name'];

	public function movie()
	{
		return $this->belongsToMany(Movie::class, 'movie_tags');
	}
}
