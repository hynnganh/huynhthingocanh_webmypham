<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Brand;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $category_list = Category::all();
        $brand_list = Brand::where('status', 1)->orderBy('sort_order', 'ASC')->get();
        
        $products = collect(); 
        if ($request->has('category_slug')) {
            $category = Category::where('slug', $request->category_slug)->first();
            if ($category) {
                $products = Product::where('category_id', $category->id)->get();
            }
        }

        return view('frontend.home', compact('category_list', 'brand_list', 'products'));
    }
}
