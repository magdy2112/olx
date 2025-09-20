<?php

namespace App\Http\Requests\Advertising;

use App\Models\CustomAttribute;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

use App\Enum\Purpose;

class Newadvertisingrequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        //   return Auth::check() ; 
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
          'title' => 'required|string|max:255',
        'description' => 'nullable|string|max:1000',
        'price' => 'nullable|numeric|min:0',
       'purpose' => ['required', new Enum(Purpose::class)],
        'category_id' => 'required|exists:categories,id',
        'sub_category_id' => 'required|exists:sub_categories,id',
        'modal_id' => 'required|exists:modals,id',
        'submodal_id' => 'nullable|exists:submodals,id',
        'status' => 'nullable|string|in:active,inactive',
        'user_id' => 'nullable|exists:users,id',
        'categoryattributes' => 'required|array',
        'categoryattributes.*.attribute_id' => 'required|integer|exists:categoryattributes,id', 
        'categoryattributes.*.value' => 'required|string|max:255',
        'images' => 'required|array|min:1|max:8',
        'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
          
        ];
   

    }

 
}
