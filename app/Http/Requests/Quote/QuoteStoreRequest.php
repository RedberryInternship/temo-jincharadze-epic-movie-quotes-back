<?php

namespace App\Http\Requests\Quote;

use Illuminate\Foundation\Http\FormRequest;

class QuoteStoreRequest extends FormRequest
{
	public function rules()
	{
		return [
			'movie_id' => ['required'],
			'quoteEn'  => ['required', 'regex:/^[-a-zA-Z0-9:",.?!() ]*$/'],
			'quoteKa'  => ['required', 'regex:/^[-ა-ჰ0-9:",.?!() ]*$/'],
			'image'    => ['required'],
		];
	}
}
