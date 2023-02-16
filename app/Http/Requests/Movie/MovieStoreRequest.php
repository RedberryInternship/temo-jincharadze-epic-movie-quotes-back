<?php

namespace App\Http\Requests\Movie;

use Illuminate\Foundation\Http\FormRequest;

class MovieStoreRequest extends FormRequest
{
	public function rules()
	{
		return [
			'user_id'       => ['required'],
			'nameEn'        => ['required', 'regex:/^[a-zA-Z0-9 ]*$/'],
			'nameKa'        => ['required', 'regex:/^[ა-ჰ0-9 ]*$/'],
			'directorEn'    => ['required', 'regex:/^[a-zA-Z ]*$/'],
			'directorKa'    => ['required', 'regex:/^[ა-ჰ ]*$/'],
			'descriptionEn' => ['required', 'regex:/^[a-zA-Z0-9",.?!() ]*$/'],
			'descriptionKa' => ['required', 'regex:/^[ა-ჰ0-9",.?!() ]*$/'],
			'budget'        => ['required'],
			'year'          => ['required'],
			'image'         => ['required'],
			'tags'          => ['required'],
		];
	}
}
