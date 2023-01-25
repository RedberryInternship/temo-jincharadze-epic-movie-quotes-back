<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;

class CommentStoreRequest extends FormRequest
{
	public function rules()
	{
		return [
			'user_id'  => ['required'],
			'quote_id' => ['required'],
			'comment'  => ['required'],
		];
	}
}
