<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductReview;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:product,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video' => 'nullable|mimetypes:video/mp4,video/avi,video/mpeg,video/quicktime|max:10240',
        ]);

        $user = Auth::user();
        if (!$user) {
            return redirect()->back()->with('error', 'Bạn cần đăng nhập để đánh giá.');
        }

        $productId = $request->product_id;

        // ✅ Chỉ cho phép đánh giá nếu user có đơn hàng giao thành công (status = 5)
        $orderExists = DB::table('order')
            ->join('orderdetail', 'order.id', '=', 'orderdetail.order_id')
            ->where('order.user_id', $user->id)
            ->where('orderdetail.product_id', $productId)
            ->where('order.status', 5)
            ->exists();

        if (!$orderExists) {
            return redirect()->back()->with('error', 'Bạn chỉ có thể đánh giá sản phẩm đã giao thành công.');
        }

        // ✅ Upload ảnh & video vào thư mục public/assets/images/videos/reviews
        $imageUrl = null;
        $videoUrl = null;

        // Thư mục lưu
        $imagePath = public_path('assets/images/reviews');
        $videoPath = public_path('assets/videos/reviews');

        // Tạo thư mục nếu chưa có
        if (!File::exists($imagePath)) {
            File::makeDirectory($imagePath, 0775, true);
        }
        if (!File::exists($videoPath)) {
            File::makeDirectory($videoPath, 0775, true);
        }

        // ✅ Upload ảnh
        if ($request->hasFile('image')) {
            $imageName = time() . '_' . uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($imagePath, $imageName);
            $imageUrl = 'assets/images/reviews/' . $imageName;
        }

        // ✅ Upload video
        if ($request->hasFile('video')) {
            $videoName = time() . '_' . uniqid() . '.' . $request->file('video')->getClientOriginalExtension();
            $request->file('video')->move($videoPath, $videoName);
            $videoUrl = 'assets/videos/reviews/' . $videoName;
        }

        // ✅ Lưu hoặc cập nhật review
        ProductReview::updateOrCreate(
            ['user_id' => $user->id, 'product_id' => $productId],
            [
                'rating' => $request->rating,
                'comment' => $request->comment,
                'image' => $imageUrl,
                'video' => $videoUrl,
            ]
        );

        return redirect()->back()->with('success', 'Đánh giá của bạn đã được lưu!');
    }

    // ✅ Hiển thị danh sách đánh giá
    public function show($productId)
    {
        $reviews = ProductReview::with('user')
            ->where('product_id', $productId)
            ->latest()
            ->get();

        $user = Auth::user();

        // Kiểm tra user có được phép đánh giá không
        $canReview = false;
        if ($user) {
            $canReview = DB::table('order')
                ->join('orderdetail', 'order.id', '=', 'orderdetail.order_id')
                ->where('order.user_id', $user->id)
                ->where('orderdetail.product_id', $productId)
                ->where('order.status', 5)
                ->exists();
        }

        return view('frontend.product.review', compact('reviews', 'productId', 'canReview'));
    }
}
