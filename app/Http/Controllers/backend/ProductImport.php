<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Product([
            'category_id' => $row['category_id'] ?? 1,
            'brand_id'    => $row['brand_id'] ?? 1,
            'name'        => $row['name'],
            'slug'        => \Str::slug($row['name']),
            'price_root'  => $row['price_root'] ?? 0,
            'price_sale'  => $row['price_sale'] ?? 0,
            'thumbnail'   => 'srmhadalabo.webp',
            'qty'         => $row['qty'] ?? 0,
            'detail'      => $row['detail'] ?? '',
            'description' => $row['description'] ?? '',
            'created_by'  => 1,
            'status'      => 1,
        ]);
    }
}
