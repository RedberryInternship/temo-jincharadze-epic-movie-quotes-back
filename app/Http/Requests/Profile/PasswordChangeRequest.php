<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class PasswordChangeRequest extends FormRequest
{
	public function rules()
	{
		return [
			'password'         => ['required', 'min:8', 'max:15', 'regex:/^[a-z0-9]*$/'],
			'confirm_password' => ['required', 'same:password'],
		];
	}
}
