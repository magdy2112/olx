<?php

namespace App\Http\Requests\Category;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;


class Addcategoryrequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
     
  return Auth::check() && Auth::user()->role === 'admin';     
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:categories,name'
        ];
    }
}
