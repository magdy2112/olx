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
            'lat'=>'numeric|max:255',
            'lng'=>'numeric|max:255',
            'city' => 'string|max:255|exists:governorates,city',
            'country' => 'string|max:255|exists:governorates,country',
            //  'governorate_id' => 'required|exists:governorates,id',
        ];
    }
}
