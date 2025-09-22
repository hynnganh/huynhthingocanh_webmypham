<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBrandRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function rules(): array
{
    return [
        'name' => 'required|unique:brand',
        'description' => 'nullable',
        'status' => 'required|in:0,1',
    ];
}

public function messages(): array
{
    return [
        'name.required' => 'Tên thương hiệu không để trống',
        'name.unique' => 'Tên thương hiệu đã tồn tại',
        'status.required' => 'Trạng thái không để trống',
        'status.in' => 'Trạng thái không hợp lệ',
    ];
}
}
