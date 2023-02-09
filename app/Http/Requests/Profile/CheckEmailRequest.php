<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class CheckEmailRequest extends FormRequest
{
	public function rules()
	{
		return [
			'email'            => ['required', 'email:strict', 'unique:emails,email'],
		];
	}
}
