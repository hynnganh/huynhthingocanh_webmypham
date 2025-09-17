<?php

namespace App\Http\Controllers\frontend;

use App\Models\Category;
use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $category_list = Category::all();
        $products = collect(); 
        if ($request->has('category_slug')) {
            $category = Category::where('slug', $request->category_slug)->first();
            if ($category) {
                $products = Product::where('category_id', $category->id)->get();
            }
        }
        return view('frontend.home', compact('category_list', 'products'));
    }

    
}
