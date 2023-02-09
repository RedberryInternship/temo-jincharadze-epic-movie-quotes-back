<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UsernameStoreRequest extends FormRequest
{
	public function rules()
	{
		return [
			'username' => ['required', 'min:3', 'max:15', 'regex:/^[a-z0-9]*$/'],
		];
	}
}
