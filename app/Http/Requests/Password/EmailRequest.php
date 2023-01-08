<?php

namespace App\Http\Requests\Password;

use Illuminate\Foundation\Http\FormRequest;

class EmailRequest extends FormRequest
{
	public function rules()
	{
		return [
			'email' => ['required', 'email', 'exists:emails,email'],
		];
	}
}
