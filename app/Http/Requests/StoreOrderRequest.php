<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
        'user_id' => 'required|exists:user,id',
        'product_id' => 'required|array',
        'quantity' => 'required|array|min:1',
        'total_price' => 'required|numeric|min:0',
        'status' => 'required|in:0,1',
    ];
}

public function messages(): array
{
    return [
        'user_id.required' => 'Chưa chọn người dùng',
        'user_id.exists' => 'Người dùng không tồn tại',
        'product_id.required' => 'Chưa chọn sản phẩm',
        'quantity.required' => 'Số lượng không để trống',
        'quantity.min' => 'Số lượng sản phẩm không hợp lệ',
        'total_price.required' => 'Tổng giá không để trống',
        'total_price.numeric' => 'Tổng giá phải là một số',
        'total_price.min' => 'Tổng giá không hợp lệ',
        'status.required' => 'Trạng thái không để trống',
        'status.in' => 'Trạng thái không hợp lệ',
    ];
}

}
