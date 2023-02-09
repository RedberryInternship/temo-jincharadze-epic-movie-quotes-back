<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class GoogleProfileRequest extends FormRequest
{
	public function rules()
	{
		return [
			'username'         => ['required', 'min:3', 'max:15', 'regex:/^[a-z0-9]*$/'],
			'userId'           => ['required'],
			'image'            => ['required'],
		];
	}
}
