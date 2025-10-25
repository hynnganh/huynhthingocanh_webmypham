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
        // 🔹 Lấy danh mục thật từ bảng category
        $categories = DB::table('category')->pluck('name', 'id')->toArray();

        // 🔹 Lấy brand thật từ bảng brand (nếu có)
        $brandIds = DB::table('brand')->pluck('id')->toArray();

        for ($i = 1; $i <= 1000; $i++) {
            // Random danh mục & thương hiệu
            $categoryId = array_rand($categories);
            $brandId = $brandIds[array_rand($brandIds)];

            $categoryName = $categories[$categoryId];

            // 🔹 Tên sản phẩm dựa theo danh mục
            $productName = $categoryName . ' ' . fake()->word() . ' ' . $i;

            // 🔹 Dùng 1 ảnh duy nhất
            $thumbnail = 'srmhadalabo.jpg';

            DB::table('product')->insert([
                'category_id' => $categoryId,
                'brand_id' => $brandId,
                'name' => $productName,
                'slug' => Str::slug($productName),
                'price_root' => rand(80000, 300000),
                'price_sale' => rand(60000, 250000),
                'thumbnail' => $thumbnail,
                'qty' => rand(10, 200),
                'detail' => 'Chi tiết về ' . $productName . ' với chất lượng cao cấp.',
                'description' => 'Mô tả ngắn cho ' . $productName . '. Phù hợp với mọi loại da.',
                'created_by' => 1,
                'created_at' => now(),
                'status' => 1,
            ]);
        }
    }
}
