<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LocationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'lat'=>'required_without:city|required_with:country|numeric|exists:governorates,lat',
            'lng'=>'required_without:city|required_with:country|numeric|exists:governorates,lng',
            'city' => 'required_without:lat|required_with:lng|string|max:255|exists:governorates,city',
            'country' => 'required_without:lat|required_with:lng|string|max:255|exists:governorates,country',

        ];
    }
}
