<?php

namespace App\Http\Requests\Movie;

use Illuminate\Foundation\Http\FormRequest;

class MovieStoreRequest extends FormRequest
{
	public function rules()
	{
		return [
			'user_id'       => ['required'],
			'nameEn'        => ['required'],
			'nameKa'        => ['required'],
			'directorEn'    => ['required'],
			'directorKa'    => ['required'],
			'descriptionEn' => ['required'],
			'descriptionKa' => ['required'],
			'budget'        => ['required'],
			'year'          => ['required'],
			'image'         => ['required'],
			'tags'          => ['required'],
		];
	}
}
