<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Lấy dữ liệu tổng hợp
        $totalOrders = Order::count(); // Tổng số đơn hàng
        $totalProducts = Product::count(); // Tổng số sản phẩm
        $totalUsers = User::count(); // Tổng số người dùng
        $totalCategories = Category::count(); // Tổng số danh mục
        $totalBrands = Brand::count(); // Tổng số thương hiệu
        $completedOrders = Order::where('status', 3)->count();
        $processingOrders = Order::where('status', 1)->count();
        $shippingOrders  = Order::where('status', 2)->count();
        $cancelledOrders = Order::where('status', 4)->count();

        // 1. Lấy Top Sản phẩm Bán chạy nhất
        // Lưu ý: Đã sửa tên bảng từ order_detail thành orderdetail và cột quantity thành qty.
        $topSellingProducts = Product::select('product.id', 'product.name', 'product.thumbnail')
            
            // FIX: Sử dụng cột 'qty' và tên bảng 'orderdetail'
            ->selectRaw('SUM(orderdetail.qty) as total_sold')
            
            // Lấy tên danh mục (Giả định có Model Category trỏ đến bảng 'category')
            ->selectRaw('category.name as categoryname')
            
            // FIX: Tham gia với bảng chi tiết đơn hàng là 'orderdetail'
            ->join('orderdetail', 'product.id', '=', 'orderdetail.product_id')
            
            // Tham gia với bảng danh mục (Giả định tên bảng là 'category')
            ->leftJoin('category', 'product.category_id', '=', 'category.id')
            
            // Cần GROUP BY tất cả các cột không được tính toán trong SELECT
            ->groupBy('product.id', 'product.name', 'product.thumbnail', 'category.name')
            
            // Sắp xếp giảm dần theo tổng số lượng đã bán
            ->orderByDesc('total_sold')
            
            // Lấy 10 sản phẩm hàng đầu
            ->limit(10)
            ->get();

        return view('backend.admin.dashboard', [
            'totalOrders' => $totalOrders,
            'totalProducts' => $totalProducts,
            'totalUsers' => $totalUsers,
            'totalCategories' => $totalCategories,
            'totalBrands' => $totalBrands,
            'completedOrders' => $completedOrders,
            'processingOrders' => $processingOrders,
            'shippingOrders' => $shippingOrders,
            'cancelledOrders' => $cancelledOrders,
            // 2. Truyền biến mới vào view
            'topSellingProducts' => $topSellingProducts, 
        ]);

    }
}
