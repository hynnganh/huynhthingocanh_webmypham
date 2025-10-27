<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductReview;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Thêm DB Facade

class ProductController extends Controller
{
public function index(Request $request)
{
    $args = [['status', '=', 1]];
    $listCategoryIds = [];

    // === 1️⃣ Lọc theo danh mục (nếu có) ===
    $categorySlugs = (array) $request->input('category_slug', []);
    if (!empty($categorySlugs)) {
        $selectedCategories = Category::where('status', 1)
            ->whereIn('slug', $categorySlugs)
            ->get();

        foreach ($selectedCategories as $category) {
            // Lấy luôn danh mục cha + con
            $listCategoryIds = array_merge($listCategoryIds, $this->getListCategory($category->id));
        }
        $listCategoryIds = array_unique($listCategoryIds);
    }

    // Query gốc
    $productQuery = Product::where($args);

    // Áp dụng lọc danh mục nếu có
    if (!empty($listCategoryIds)) {
        $productQuery->whereIn('category_id', $listCategoryIds);
    }

    // === 2️⃣ Lọc theo thương hiệu (nếu có) ===
    $brandSlugs = (array) $request->input('brand_slug', []);
    if (!empty($brandSlugs)) {
        $brandIds = Brand::where('status', 1)
            ->whereIn('slug', $brandSlugs)
            ->pluck('id')
            ->toArray();

        if (!empty($brandIds)) {
            $productQuery->whereIn('brand_id', $brandIds);
        }
    }

    // === 3️⃣ Lọc theo khoảng giá (chỉ khi người dùng chọn) ===
    $min = $request->filled('min') ? (int) $request->input('min') : null;
    $max = $request->filled('max') ? (int) $request->input('max') : null;

    if (!is_null($min) && !is_null($max)) {
        $productQuery->whereBetween('price_sale', [$min, $max]);
    } elseif (!is_null($min)) {
        $productQuery->where('price_sale', '>=', $min);
    } elseif (!is_null($max)) {
        $productQuery->where('price_sale', '<=', $max);
    }

    // === 4️⃣ Sắp xếp ===
    $sort = $request->input('sort');
    switch ($sort) {
        case 'asc':
            $productQuery->orderBy('price_sale', 'asc');
            break;
        case 'desc':
            $productQuery->orderBy('price_sale', 'desc');
            break;
        default:
            $productQuery->orderBy('created_at', 'desc');
            break;
    }

    // === 5️⃣ Phân trang ===
    $product_list = $productQuery->paginate(50)->appends($request->query());

    // === 6️⃣ Dữ liệu filter (danh mục + thương hiệu) ===
    $category_list = Category::where('status', 1)->get();
    $brand_list = Brand::where('status', 1)->get();

    return view('frontend.product', compact('product_list', 'category_list', 'brand_list'));
}



    // Hàm search
    public function search(Request $request)
    {
        $query = $request->input('query');

        if ($query) {
            $products = Product::where('status', 1) 
                ->where(function($queryBuilder) use ($query) {
                    $queryBuilder->where('name', 'like', '%' . $query . '%')
                                 ->orWhere('description', 'like', '%' . $query . '%');
                })
                ->paginate(50); // Phân trang 50 kết quả tìm kiếm

        } else {
            $products = Product::where('status', 1)->paginate(50);
        }
        
        return view('frontend.search', compact('products', 'query'));
    }

    // Hàm detail
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

        $reviews = $product->reviews()->latest()->get();
        $averageRating = $product->reviews()->avg('rating');

        $canReview = false;
        $user = Auth::user();
        if ($user) {
            $canReview = DB::table('order')
                ->join('orderdetail', 'order.id', '=', 'orderdetail.order_id')
                ->where('order.user_id', $user->id)
                ->where('orderdetail.product_id', $product->id)
                ->where('order.status', 5) 
                ->exists();
        }

        return view('frontend.product-detail', compact('product', 'product_list', 'reviews', 'averageRating', 'canReview'));
    }

    // Hàm đệ quy lấy danh sách category con
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