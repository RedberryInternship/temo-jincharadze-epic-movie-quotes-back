<?php

namespace App\Http\Requests\Like;

use Illuminate\Foundation\Http\FormRequest;

class LikeRequest extends FormRequest
{
	public function rules()
	{
		return [
			'user_id'  => ['required'],
			'quote_id' => ['required'],
		];
	}
}
