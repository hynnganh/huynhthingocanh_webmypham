<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:user|max:15',
            'email' => 'required|email|unique:user,email',
            'address' => 'required|string|max:255',
            'username' => 'required|string|unique:user,username|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên không được để trống.',
            'name.string' => 'Tên phải là chuỗi ký tự.',
            'name.max' => 'Tên không được vượt quá 255 ký tự.',

            'phone.required' => 'Số điện thoại không được để trống.',
            'phone.string' => 'Số điện thoại phải là chuỗi ký tự.',
            'phone.max' => 'Số điện thoại không được vượt quá 15 ký tự.',
            'phone.unique' => 'Số điện thoại đã tồn tại trong hệ thống.',

            'email.required' => 'Email không được để trống.',
            'email.email' => 'Định dạng email không hợp lệ.',
            'email.unique' => 'Email đã tồn tại trong hệ thống.',

            'address.required' => 'Địa chỉ không được để trống.',
            'address.string' => 'Địa chỉ phải là chuỗi ký tự.',
            'address.max' => 'Địa chỉ không được vượt quá 255 ký tự.',

            'username.required' => 'Tên đăng nhập không được để trống.',
            'username.string' => 'Tên đăng nhập phải là chuỗi ký tự.',
            'username.unique' => 'Tên đăng nhập đã tồn tại.',
            'username.max' => 'Tên đăng nhập không được vượt quá 255 ký tự.',

            'avatar.image' => 'Tập tin phải là hình ảnh.',
            'avatar.mimes' => 'Hình ảnh phải có định dạng jpeg, png, jpg, gif hoặc svg.',
            'avatar.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',

            'password.required' => 'Mật khẩu không được để trống.',
            'password.string' => 'Mật khẩu phải là chuỗi ký tự.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ];
    }
}
