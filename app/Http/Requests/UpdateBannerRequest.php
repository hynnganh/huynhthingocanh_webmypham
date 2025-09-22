<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBannerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function rules(): array
{
    return [
        'name' => 'required',
        'description' => 'nullable|string',
        'status' => 'required|in:0,1',
        'sort_order' => 'nullable|integer',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'position' => 'required|in:slideshow,ads',

    ];
}

public function messages(): array
{
    return [
        'name.required' => 'Tên banner không để trống',
        'status.required' => 'Trạng thái không để trống',
        'status.in' => 'Trạng thái không hợp lệ',
        'image.image' => 'Không phải hình ảnh',
        'image.mimes' => 'Định dạng tập tin không đúng',
        'position.required' => 'Vị trí không được để trống',
        'position.in' => 'Vị trí không hợp lệ',

    ];
}
}
