<?php

namespace App\Http\Requests\Attributes;
use Illuminate\Support\Facades\Auth;

use Illuminate\Foundation\Http\FormRequest;

class Updateattributerequest extends FormRequest
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
            'name'=> 'string',
            'sub_category_id'=> 'exists:sub_categories,id|integer',
        ];
    }
}
