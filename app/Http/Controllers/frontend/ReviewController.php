<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductReview;
use Illuminate\Support\Facades\Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

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

        $orderIds = \DB::table('order')
            ->join('orderdetail', 'order.id', '=', 'orderdetail.order_id')
            ->where('order.user_id', $user->id)
            ->where('orderdetail.product_id', $productId)
            ->where('order.status', 5)
            ->pluck('order.id');

        if ($orderIds->isEmpty()) {
            return redirect()->back()->with('error', 'Bạn chỉ có thể đánh giá sản phẩm đã giao thành công.');
        }

        // ✅ Upload ảnh & video lên Cloudinary
        $imageUrl = null;
        $videoUrl = null;

        if ($request->hasFile('image')) {
            $imageUpload = Cloudinary::upload(
                $request->file('image')->getRealPath(),
                ['folder' => 'product_reviews/images']
            );
            $imageUrl = $imageUpload->getSecurePath();
        }

        if ($request->hasFile('video')) {
            $videoUpload = Cloudinary::uploadVideo(
                $request->file('video')->getRealPath(),
                ['folder' => 'product_reviews/videos']
            );
            $videoUrl = $videoUpload->getSecurePath();
        }

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
}
