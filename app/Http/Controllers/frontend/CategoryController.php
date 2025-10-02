<?php

namespace App\Http\Controllers\frontend;
use App\Models\Category;
use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function showCategory($slug)
    {
        $category = Category::where('slug', $slug)
                            ->where('status', 1)
                            ->firstOrFail();

        $products = Product::where('category_id', $category->id) ->where('status', 1)->paginate(12);

        return view('frontend.category.show', compact('category', 'products'));
    }
}

