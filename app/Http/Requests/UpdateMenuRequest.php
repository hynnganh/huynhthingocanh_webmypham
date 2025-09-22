<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMenuRequest extends FormRequest
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
            'link' => 'required',
            'status' => 'required|in:0,1',
            'position' => 'required|in:mainmenu,footer',
        ];
    }
    
    public function messages(): array
    {
        return [
            'name.required' => 'Tên menu không để trống',
            'link.required' => 'Link không để trống',
            'link.url' => 'Link phải là một URL hợp lệ',
            'status.required' => 'Trạng thái không để trống',
            'status.in' => 'Trạng thái không hợp lệ',
        ];
}
}