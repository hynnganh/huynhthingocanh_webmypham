<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 1000; $i++) {
            DB::table('product')->insert([
                'category_id' => rand(1, 5), // ngẫu nhiên giữa 5 danh mục
                'brand_id' => rand(1, 5),    // ngẫu nhiên giữa 5 thương hiệu
                'name' => 'Sản phẩm mẫu ' . $i,
                'slug' => Str::slug('Sản phẩm mẫu ' . $i),
                'price_root' => 50000 + ($i * 10),
                'price_sale' => 40000 + ($i * 10),
                'thumbnail' => 'srmhadalabo.jpg',
                'qty' => rand(10, 200),
                'detail' => 'Chi tiết sản phẩm mẫu số ' . $i,
                'description' => 'Mô tả sản phẩm mẫu số ' . $i,
                'created_by' => 1,
                'created_at' => now(),
                'status' => 1,
            ]);
        }
    }
}
