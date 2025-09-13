<?php

namespace App\Http\Requests\Advertising;

use App\Models\CustomAttribute;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class Newadvertisingrequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        //   return Auth::check() ; 
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'nullable|numeric|min:0',
            'purpose' => 'required|in:sell,buy',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'modal_id' => 'nullable|exists:modals,id',
            'submodal_id' => 'nullable|exists:submodals,id',
            'status' => 'nullable|string|in:active,inactive',
            'user_id' => 'nullable|exists:users,id',
               'attributes' => 'nullable|array',
            'attributes.*' => 'nullable|string|max:255',
          
        ];
   

    }

 
}
