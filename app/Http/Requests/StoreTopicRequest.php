<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTopicRequest extends FormRequest
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
        'name' => 'required|unique:topic',
        'status' => 'required|in:0,1',
    ];
}

public function messages(): array
{
    return [
        'name.required' => 'Tên chủ đề không để trống',
        'name.unique' => 'Tên chủ đề đã tồn tại',
        'status.required' => 'Trạng thái không để trống',
        'status.in' => 'Trạng thái không hợp lệ',
    ];
}

}
