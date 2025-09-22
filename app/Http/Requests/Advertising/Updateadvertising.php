<?php

namespace App\Http\Requests\Advertising;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

use Illuminate\Validation\Rules\Enum;

use App\Enum\Purpose;

class Updateadvertising extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
          return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
        'title' => 'string|max:255',
        'description' => 'nullable|string|max:1000',
        'price' => 'nullable|numeric|min:0',
       'purpose' => [ new Enum(Purpose::class)],
        'category_id' => 'exists:categories,id',
        'sub_category_id' => 'exists:sub_categories,id',
        'modal_id' => 'exists:modals,id',
        'submodal_id' => 'nullable|exists:submodals,id',
        'status' => 'nullable|string|in:active,inactive',
        'user_id' => 'nullable|exists:users,id',
        'categoryattributes' => 'array',
        'categoryattributes.*.attribute_id' => 'integer|exists:categoryattributes,id', 
        'categoryattributes.*.value' => 'string|max:255',
        'images' => 'array|min:1|max:8',
        'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ];
    }
}
