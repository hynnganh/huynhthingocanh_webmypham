<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
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
            'title' => 'required',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
            'detail' => 'required',
            'topic_id' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Tên không để trống',
            'thumbnail.image' => 'Không phải hình ảnh',
            'thumbnail.mimes' => 'Định dạng tập tin không đúng',
            'detail.required' => 'Chi tiết không để trống',
            'brand_id.required' => 'Chưa chọn topic',
        ];
    }
}
