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

	public function scopeFilter($query, array $filters)
	{
		if ($filters['search'] ?? false)
		{
			if (app()->getLocale() === 'en')
			{
				if (str_starts_with($filters['search'], '@'))
				{
					$movieName = substr($filters['search'], 1);
					$query->whereHas('movie', function ($q) use ($movieName) {
						$q->where('name->en', 'like', '%' . (ucwords($movieName)) . '%');
					});
				}
				if (str_starts_with($filters['search'], '#'))
				{
					$movieName = substr($filters['search'], 1);
					$query->where('text-en', 'like', '%' . (ucwords($filters['search']) . '%'));
				}
			}
			if (app()->getLocale() === 'ka')
			{
				if (str_starts_with($filters['search'], '@'))
				{
					$movieName = substr($filters['search'], 1);
					$query->whereHas('movie', function ($q) use ($movieName) {
						$q->where('name->ka', 'like', '%' . (ucwords($movieName)) . '%');
					});
				}
				if (str_starts_with($filters['search'], '#'))
				{
					$movieName = substr($filters['search'], 1);
					$query->where('text-ka', 'like', '%' . (ucwords($filters['search']) . '%'));
				}
			}
		}
	}

	public function movie()
	{
		return $this->belongsTo(Movie::class);
	}

	public function likes()
	{
		return $this->hasMany(Like::class);
	}

	public function comments()
	{
		return $this->hasMany(Comment::class);
	}
}
