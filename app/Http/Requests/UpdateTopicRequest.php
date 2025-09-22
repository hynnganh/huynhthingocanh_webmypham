<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTopicRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required',
            'status' => 'required|in:0,1',
        ];
    }
    
    public function messages(): array
    {
        return [
            'name.required' => 'Tên chủ đề không để trống',
            'status.required' => 'Trạng thái không để trống',
            'status.in' => 'Trạng thái không hợp lệ',
        ];
    }
}
