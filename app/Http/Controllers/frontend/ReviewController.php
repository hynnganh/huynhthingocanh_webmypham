<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductReview;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:product,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        if (!$user) {
            return redirect()->back()->with('error', 'Bạn cần đăng nhập để đánh giá.');
        }

        $productId = $request->product_id;

        $orderIds = \DB::table('order')
            ->join('orderdetail', 'order.id', '=', 'orderdetail.order_id')
            ->where('order.user_id', $user->id)
            ->where('orderdetail.product_id', $productId)
            ->where('order.status', 5) // Chỉ những đơn hàng đã giao thành công
            ->pluck('order.id');

        if ($orderIds->isEmpty()) {
            return redirect()->back()->with('error', 'Bạn chỉ có thể đánh giá sản phẩm đã giao thành công.');
        }

        ProductReview::updateOrCreate(
            ['user_id' => $user->id, 'product_id' => $productId],
            ['rating' => $request->rating, 'comment' => $request->comment]
        );

        return redirect()->back()->with('success', 'Đánh giá của bạn đã được lưu!');
    }
}
