<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
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
        'name' => 'required',
        'email' => 'required|email',
        'status' => 'required|in:0,1',
        'phone' => 'required|regex:/^[0-9]{9,15}$/',
        'title' => 'nullable|string|max:255', 
        'content' => 'nullable|string',
        'reply_content' => 'nullable|string|max:2000',
    ];
}

public function messages(): array
{
    return [
        'name.required' => 'Tên không để trống',
        'email.required' => 'Email không để trống',
        'email.email' => 'Email không hợp lệ',
        'phone.required' => 'Số điện thoại không để trống',
        'phone.regex' => 'Số điện thoại không hợp lệ',
        'status.required' => 'Trạng thái không để trống',
        'status.in' => 'Trạng thái không hợp lệ',
    ];
}

}
