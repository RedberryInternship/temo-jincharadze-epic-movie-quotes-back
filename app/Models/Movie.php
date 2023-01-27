<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Movie extends Model
{
	use HasTranslations;

	use HasFactory;

	public $translatable = ['name', 'director', 'description'];

	protected $fillable = [
		'user_id',
		'name',
		'director',
		'description',
		'budget',
		'year',
		'image',
	];

	public function scopeFilter($query, array $filters)
	{
		if ($filters['search'] ?? false)
		{
			if (app()->getLocale() === 'en')
			{
				$query->where('name->en', 'like', '%' . (ucwords($filters['search'])) . '%');
			}
			if (app()->getLocale() === 'ka')
			{
				$query->where('name->ka', 'like', '%' . (ucwords($filters['search'])) . '%');
			}
		}
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function tag()
	{
		return $this->belongsToMany(Tag::class, 'movie_tags');
	}

	public function quotes()
	{
		return $this->hasMany(Quote::class);
	}
}
