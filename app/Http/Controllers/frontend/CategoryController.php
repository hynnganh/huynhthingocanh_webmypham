<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class CategoryController extends Controller
{
    public function showCategory($slug)
    {
        $category = Category::where('slug', $slug)
                            ->where('status', 1)
                            ->first();

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $products = Product::where('category_id', $category->id)
                            ->where('status', 1)
                            ->paginate(12);

        return response()->json([
            'category' => $category,
            'products' => $products
        ]);
    }
}
