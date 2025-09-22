<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBannerRequest extends FormRequest
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
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
        'status' => 'required|in:0,1',
        'name' => 'required|unique:banner',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
    ];
}

public function messages(): array
{
    return [
        'image.image' => 'Tập tin không phải hình ảnh',
        'image.mimes' => 'Định dạng hình ảnh không hợp lệ',
        'status.required' => 'Trạng thái không để trống',
        'status.in' => 'Trạng thái không hợp lệ',
        'name.required' => 'Tên không để trống',
        'name.unique' => 'Tên đã tồn tại',
    ];


            
       
    



}}
