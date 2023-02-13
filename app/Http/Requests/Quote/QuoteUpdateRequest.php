<?php

namespace App\Http\Requests\Quote;

use Illuminate\Foundation\Http\FormRequest;

class QuoteUpdateRequest extends FormRequest
{
	public function rules()
	{
		return [
			'quoteEn'  => ['required', 'regex:/^[a-zA-Z0-9",.?!() ]*$/'],
			'quoteKa'  => ['required', 'regex:/^[ა-ჰ0-9",.?!() ]*$/'],
			'image'    => ['required'],
		];
	}
}
