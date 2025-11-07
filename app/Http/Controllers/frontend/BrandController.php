<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Product;

class BrandController extends Controller
{
    public function show($slug)
    {
        $brand = Brand::where('slug', $slug)->firstOrFail();

        // Lấy danh sách sản phẩm theo thương hiệu
        $products = Product::where('brand_id', $brand->id)
            ->where('status', 1)
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('frontend.brand.show', compact('brand', 'products'));
    }
}
