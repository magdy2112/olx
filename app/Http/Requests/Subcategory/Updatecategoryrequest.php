<?php
namespace App\Http\Requests\SubCategory;
use Illuminate\Support\Facades\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
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
            'name' => 'required|string|unique:sub_categories,name',
            'category_id' => 'required|exists:categories,id',
        ];
    }
}
