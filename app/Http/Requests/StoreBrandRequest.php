<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBrandRequest extends FormRequest
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
        'name' => 'required|unique:brand',
        'description' => 'nullable|string',
        'status' => 'required|in:0,1',
        'sort_order' => 'nullable|integer',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
    ];
}

public function messages(): array
{
    return [
        'name.required' => 'Tên thương hiệu không để trống',
        'name.unique' => 'Tên thương hiệu đã tồn tại',
        'status.required' => 'Trạng thái không để trống',
        'status.in' => 'Trạng thái không hợp lệ',
        'image.image' => 'Không phải hình ảnh',
        'image.mimes' => 'Định dạng tập tin không đúng',
    ];
}

}
