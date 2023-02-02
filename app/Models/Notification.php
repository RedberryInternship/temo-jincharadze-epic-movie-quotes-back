<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
	use HasFactory;

	protected $fillable = ['user_id', 'quote_id', 'has_new', 'has_comment', 'sender_id'];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function sender()
	{
		return $this->belongsTo(User::class, 'sender_id', 'id');
	}

	public function quote()
	{
		return $this->belongsTo(Quote::class, 'quote_id', 'id');
	}
}
