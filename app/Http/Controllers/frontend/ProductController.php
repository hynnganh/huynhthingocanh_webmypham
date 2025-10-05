<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductReview;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $args = [['status', '=', 1]];
        $listCategoryIds = [];

        // Lọc theo category_slug
        if ($request->category_slug) {
            $category = Category::where([['status', '=', 1], ['slug', '=', $request->category_slug]])->first();
            if ($category) {
                $listCategoryIds = $this->getListCategory($category->id);
            }
        }

        // Lọc theo brand_slug
        if ($request->brand_slug) {
            $brand = Brand::where([['status', '=', 1], ['slug', '=', $request->brand_slug]])->first();
            if ($brand) {
                $args[] = ['brand_id', '=', $brand->id];
            }
        }

        $productQuery = Product::where($args);
        if (!empty($listCategoryIds)) {
            $productQuery->whereIn('category_id', $listCategoryIds);
        }

        // Lọc theo giá
        if ($request->filled('min') || $request->filled('max')) {
            $min = max(0, $request->input('min', 0));
            $max = $request->input('max', 1000000000);
            $productQuery->whereBetween('price_sale', [$min, $max]);
        }

        // Sắp xếp
        $sort = $request->input('sort');
        if ($sort == 'asc') $productQuery->orderBy('price_sale', 'asc');
        elseif ($sort == 'desc') $productQuery->orderBy('price_sale', 'desc');
        else $productQuery->orderBy('created_at', 'desc');

        $product_list = $productQuery->paginate(6)->withQueryString();
        $category_list = Category::where('status', 1)->get();
        $brand_list = Brand::where('status', 1)->get();

        return view('frontend.product', compact('product_list', 'category_list', 'brand_list'));
    }

    public function detail($slug)
    {
        $product = Product::where([['status', '=', 1], ['slug', '=', $slug]])->first();
        if (!$product) abort(404);

        $listCategoryIds = $this->getListCategory($product->category_id);
        $product_list = Product::where('status', 1)
            ->whereIn('category_id', $listCategoryIds)
            ->where('id', '!=', $product->id)
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        // Lấy đánh giá
        $reviews = $product->reviews()->latest()->get();
        $averageRating = $product->reviews()->avg('rating');

        // Kiểm tra user có được đánh giá hay không
        $canReview = false;
        $user = Auth::user();
        if ($user) {
            $canReview = \DB::table('order')
                ->join('orderdetail', 'order.id', '=', 'orderdetail.order_id')
                ->where('order.user_id', $user->id)
                ->where('orderdetail.product_id', $product->id)
                ->where('order.status', 5) // chỉ khi đơn hàng đã giao thành công
                ->exists();
        }

        return view('frontend.product-detail', compact('product', 'product_list', 'reviews', 'averageRating', 'canReview'));
    }

    private function getListCategory($categoryId)
    {
        $listid = [$categoryId];
        $getChildren = function ($parentId) use (&$listid, &$getChildren) {
            $children = Category::select('id')->where([['status', '=', 1], ['parent_id', '=', $parentId]])->get();
            foreach ($children as $child) {
                $listid[] = $child->id;
                $getChildren($child->id);
            }
        };
        $getChildren($categoryId);
        return $listid;
    }

    

}
