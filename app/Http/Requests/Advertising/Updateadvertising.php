<?php

namespace App\Http\Requests\Advertising;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;
use App\Models\Modal;   
use Illuminate\Validation\Rules\Enum;
use App\Models\SubModal;
use App\Enum\Purpose;
use App\Models\SubCategory;

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
        'city' => 'nullable|string|max:255',
        'country' => 'nullable|string|max:255',
        'lat' => 'nullable|numeric',
        'lng' => 'nullable|numeric',
        ];
    }


public function after(): array
{
    return [
        function (Validator $validator) {
            if ($this->validated('sub_category_id') && \App\Models\SubCategory::where('id', $this->validated('sub_category_id'))
                ->where('category_id',$this->validated('category_id'))
                ->doesntExist()) {
                $validator->errors()->add(
                    'sub_category_id',
                    'Something is wrong with this field!'
                );
            }
            if ($this->validated('modal_id') && \App\Models\Modal::where('id', $this->validated('modal_id'))
                ->where('sub_category_id',$this->validated('sub_category_id'))
                ->doesntExist()) {
                $validator->errors()->add(
                    'modal_id',
                    'Something is wrong with this field!'
                );
            }
            if ($this->validated('submodal_id') && \App\Models\SubModal::where('id', $this->validated('submodal_id'))
                ->where('modal_id',$this->validated('modal_id'))
                ->doesntExist()) {
                $validator->errors()->add(
                    'submodal_id',
                    'Something is wrong with this field!'
                );
            }
        },

    ];
}

}
