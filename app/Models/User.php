<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
	use HasApiTokens;

	use HasFactory;

	use Notifiable;

	protected $fillable = [
		'name',
		'password',
		'google_id',
		'image',
	];

	protected $hidden = [
		'password',
		'remember_token',
	];

	protected $casts = [
		'email_verified_at' => 'datetime',
	];

	public function emails()
	{
		return $this->hasMany(Email::class);
	}

	public function movies()
	{
		return $this->hasMany(Movie::class);
	}

	public function quotes()
	{
		return $this->hasMany(Quote::class);
	}

	public function comments()
	{
		return $this->hasMany(Comment::class);
	}

	public function likes()
	{
		return $this->hasMany(Like::class);
	}

	public function notifications()
	{
		return $this->hasMany(Notification::class);
	}
}
