<?php

namespace App\Http\Requests\Image;
use Illuminate\Support\Facades\Auth;

use Illuminate\Foundation\Http\FormRequest;

class Storeimage extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
          return Auth::check() ;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'advertising_id' => 'required|exists:advertisings,id',
            'images' => 'required',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp',

        ];
    }
}
