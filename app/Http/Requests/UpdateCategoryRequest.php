<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{ 
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'name' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên không để trống',
            'image.image' => 'Không phải hình ảnh',
            'image.mimes' => 'Định dạng tập tin không đúng',
        ];
    }
}
