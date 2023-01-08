<?php

namespace App\Http\Requests\Session;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
{
	public function rules()
	{
		return [
			'login'    => ['required'],
			'password' => ['required'],
		];
	}
}
