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
        
        // Lấy các tham số filter từ request (có thể là mảng cho Multi-select)
        $categorySlugs = $request->input('category_slug');
        $brandSlugs = $request->input('brand_slug');

        // Lọc theo category_slug (Hỗ trợ nhiều slug)
        if (!empty($categorySlugs)) {
            $slugs = is_array($categorySlugs) ? $categorySlugs : [$categorySlugs];
            $selectedCategories = Category::where('status', 1)->whereIn('slug', $slugs)->get();
            
            foreach ($selectedCategories as $category) {
                // Hợp nhất các ID của category được chọn và các category con của chúng
                $listCategoryIds = array_merge($listCategoryIds, $this->getListCategory($category->id));
            }
            $listCategoryIds = array_unique($listCategoryIds);
        }

        $productQuery = Product::where($args);

        // Lọc theo brand_slug (Hỗ trợ nhiều slug)
        if (!empty($brandSlugs)) {
            $slugs = is_array($brandSlugs) ? $brandSlugs : [$brandSlugs];
            $brandIds = Brand::where('status', 1)->whereIn('slug', $slugs)->pluck('id')->toArray();
            
            if (!empty($brandIds)) {
                $productQuery->whereIn('brand_id', $brandIds);
            }
        }
        
        // Áp dụng lọc theo danh mục
        if (!empty($listCategoryIds)) {
            $productQuery->whereIn('category_id', $listCategoryIds);
        }

        // Lọc theo giá
        if ($request->filled('min') || $request->filled('max')) {
            $min = max(0, $request->input('min', 0));
            $max = $request->input('max', 1000000000); 
            $productQuery->whereBetween('price_sale', [(int)$min, (int)$max]);
        }

        // Sắp xếp
        $sort = $request->input('sort');
        if ($sort == 'asc') $productQuery->orderBy('price_sale', 'asc');
        elseif ($sort == 'desc') $productQuery->orderBy('price_sale', 'desc');
        else $productQuery->orderBy('created_at', 'desc');

        // 🔥🔥🔥 SỬA ĐỔI ĐỂ PHÂN TRANG 50 SẢN PHẨM MỖI TRANG 🔥🔥🔥
        $product_list = $productQuery->paginate(50)->withQueryString(); 

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