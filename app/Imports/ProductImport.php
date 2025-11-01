<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Illuminate\Support\Str;

class ProductImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function model(array $row)
{
    return new Product([
        'category_id' => $row['category_id'] ?? 1,
        'brand_id'    => $row['brand_id'] ?? 1,
        'name'        => $row['name'],
        'slug'        => Str::slug($row['name']),
        'price_root'  => $row['price_root'] ?? 0,
        'price_sale'  => $row['price_sale'] ?? 0,
        'qty'         => $row['qty'] ?? null,
        'thumbnail'   => 'srmhadalabo.webp',
        'detail'      => $row['detail'] ?? '',
        'description' => $row['description'] ?? '',
        'created_by'  => 1,
        'status'      => 1,
    ]);
}


    /**
     * Quy tắc kiểm tra dữ liệu
     */
public function rules(): array
{
    return [
        // Category ID
        '*.category_id' => [
            'required',
            function($attribute, $value, $fail) {
                if (!is_numeric($value) || $value <= 0) {
                    $fail('Category ID phải là số hợp lệ và lớn hơn 0.');
                }
                // Optional: kiểm tra tồn tại trong bảng categories
                if (!\App\Models\Category::where('id', $value)->exists()) {
                    $fail("Category ID $value không tồn tại trong hệ thống.");
                }
            }
        ],

        // Brand ID
        '*.brand_id' => [
            'required',
            function($attribute, $value, $fail) {
                if (!is_numeric($value) || $value <= 0) {
                    $fail('Brand ID phải là số hợp lệ và lớn hơn 0.');
                }
                // Optional: kiểm tra tồn tại trong bảng brands
                if (!\App\Models\Brand::where('id', $value)->exists()) {
                    $fail("Brand ID $value không tồn tại trong hệ thống.");
                }
            }
        ],

        // Tên sản phẩm
        '*.name' => [
            'required',
            'string',
            'max:255',
            function ($attribute, $value, $fail) {
                $slug = Str::slug($value);
                if (\App\Models\Product::where('slug', $slug)->exists()) {
                    $fail("Sản phẩm với slug '$slug' đã tồn tại.");
                }
            },
        ],

        // Giá gốc
        '*.price_root' => [
            'required',
            function ($attribute, $value, $fail) {
                if (!preg_match('/^\d+(\.\d+)?$/', trim($value))) {
                    $fail('Giá gốc phải là số hợp lệ.');
                }
                elseif ($value < 0) {
                    $fail('Giá gốc phải lớn hơn hoặc bằng 0.');
                }
            },
        ],

        // Giá bán
        '*.price_sale' => [
            'required',
            function ($attribute, $value, $fail) {
                if (!preg_match('/^\d+(\.\d+)?$/', trim($value))) {
                    $fail('Giá bán phải là số hợp lệ.');
                }
                elseif ($value < 0) {
                    $fail('Giá bán phải lớn hơn hoặc bằng 0.');
                }
            },
        ],

        // Số lượng
        '*.qty' => [
            'required',
            function($attribute, $value, $fail) {
                if (!preg_match('/^\d+$/', trim($value))) {
                    $fail('Số lượng phải là số nguyên hợp lệ.');
                } elseif ((int)$value < 0) {
                    $fail('Số lượng phải lớn hơn hoặc bằng 0.');
                }
            }
        ],


    ];
}



    /**
     * Thông báo lỗi tùy chỉnh
     */
    public function customValidationMessages()
{
    return [
        'name.required'       => 'Tên sản phẩm bị thiếu.',
        'price_root.required' => 'Giá gốc bị thiếu.',
        'price_root.numeric'  => 'Giá gốc phải là số.',
        'price_sale.required' => 'Giá bán bị thiếu.',
        'price_sale.numeric'  => 'Giá bán phải là số.',
        'qty.required'        => 'Số lượng bị thiếu.',
        'qty.integer'         => 'Số lượng phải là số nguyên.',
    ];
}

}
