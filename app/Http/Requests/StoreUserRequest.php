<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:user,username', 
            'email' => 'required|email|unique:user,email',  
            'phone' => 'required|string',
            'address' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'required|in:user,admin',
        ];
    }

    /**
     * Get custom validation error messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Tên người dùng không được để trống.',
            'username.required' => 'Username không được để trống.',
            'username.unique' => 'Username đã được sử dụng.',
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email đã được sử dụng.',
            'phone.required' => 'Số điện thoại không được để trống.',
            'password.required' => 'Mật khẩu không được để trống.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
            'roles.required' => 'Vai trò không được để trống.',
            'roles.in' => 'Vai trò không hợp lệ. Chọn giữa người dùng hoặc admin.',
            'avatar.image' => 'Ảnh đại diện phải là một tệp hình ảnh.',
            'avatar.mimes' => 'Ảnh đại diện phải có định dạng: jpeg, png, jpg, gif.',
        ];
    }
}
