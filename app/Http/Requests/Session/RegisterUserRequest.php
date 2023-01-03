<?php

namespace App\Http\Requests\Session;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
	public function rules()
	{
		return [
			'name'             => ['required', 'min:3', 'max:15', 'unique:users,name', 'regex:/^[a-z0-9]*$/'],
			'email'            => ['required', 'email:strict', 'unique:emails,email'],
			'password'         => ['required', 'min:8', 'max:15', 'regex:/^[a-z0-9]*$/'],
			'confirm_password' => ['required', 'same:password'],
		];
	}
}
