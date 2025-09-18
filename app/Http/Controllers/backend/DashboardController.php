<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Models\Dashboard;


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

        return view('backend.admin.dashboard', compact(
            'totalOrders', 
            'totalProducts', 
            'totalUsers', 
            'totalCategories', 
            'totalBrands'
        ));
    }
}
