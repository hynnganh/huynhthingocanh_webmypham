<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;


class ProductController extends Controller
{
    public function index(Request $request)
{
    $args = [['status', '=', 1]];
    $listCategoryIds = [];

    // Kiểm tra category_slug trong request và lọc theo slug
    if ($request->category_slug) {
        $category = Category::where([['status', '=', 1], ['slug', '=', $request->category_slug]])->first();
        if ($category) {
            $listCategoryIds = $this->getListCategory($category->id);
        }
    }

    if ($request->brand_slug) {
        $brand = Brand::where([['status', '=', 1], ['slug', '=', $request->brand_slug]])->first();
        if ($brand) {
            $args[] = ['brand_id', '=', $brand->id];
        }
    }

    $productQuery = Product::where($args)->orderBy('created_at', 'desc');

    if (!empty($listCategoryIds)) {
        $productQuery->whereIn('category_id', $listCategoryIds);
    }

    $product_list = $productQuery->paginate(6);

    // Lấy danh sách category và brand
    $category_list = Category::where('status', 1)->get();
    $brand_list = Brand::where('status', 1)->get();

    return view('frontend.product', compact('product_list', 'category_list', 'brand_list'));
}


    public function detail($slug)
{
    $product = Product::where([['status', '=', 1], ['slug', '=', $slug]])->first();

    if (!$product) {
        abort(404);
    }

    $listCategoryIds = $this->getListCategory($product->category_id);

    $product_list = Product::where('status', 1)
        ->whereIn('category_id', $listCategoryIds)
        ->where('id', '!=', $product->id)
        ->orderBy('created_at', 'desc')
        ->limit(4)
        ->get();

        return view('frontend.product-detail',compact('product','product_list'));
}


    private function getListCategory($categoryId)
{
    $listid = [$categoryId];

    $getChildren = function($parentId) use (&$listid, &$getChildren) {
        $children = Category::select('id')->where([['status', '=', 1], ['parent_id', '=', $parentId]])->get();
        foreach ($children as $child) {
            $listid[] = $child->id;
            $getChildren($child->id);
        }
    };

    $getChildren($categoryId);

    return $listid;
}

    public function search(Request $request)
    {
        $query = $request->input('query');

        if ($query) {
            $products = Product::where(function($queryBuilder) use ($query) {
                $queryBuilder->where('name', 'like', '%' . $query . '%')
                             ->orWhere('description', 'like', '%' . $query . '%');
            });

            $products = $products->get();
        } else {
            $products = Product::all();
        }
        
        return view('frontend.search', compact('products', 'query'));
    }
}
