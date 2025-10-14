<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Hiển thị danh sách yêu thích của người dùng hiện tại.
     */
    public function index()
    {
        $wishlist = Wishlist::where('user_id', Auth::id())
            ->with('product')
            ->get();

        return view('frontend.wishlist.index', compact('wishlist'));
    }

    /**
     * Toggle (thêm hoặc xóa) sản phẩm khỏi danh sách yêu thích.
     * Hỗ trợ cả AJAX và form submit.
     */
    public function toggle(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:product,id']);
        $user = Auth::user();
        $productId = $request->product_id;

        $existing = Wishlist::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if ($existing) {
            $existing->delete();
            $message = 'Đã xóa khỏi danh sách yêu thích!';
            $status = 'removed';
        } else {
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $productId,
            ]);
            $message = 'Đã thêm vào danh sách yêu thích!';
            $status = 'added';
        }

        // Nếu là AJAX request
        if ($request->expectsJson()) {
            return response()->json(['status' => $status, 'message' => $message]);
        }

        // Nếu là form truyền thống
        return redirect()->back()->with('success', $message);
    }

    /**
     * Xóa sản phẩm khỏi danh sách yêu thích.
     */
    public function remove(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:product,id']);

        $deleted = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->delete();

        $message = $deleted
            ? 'Đã xóa khỏi danh sách yêu thích!'
            : 'Sản phẩm không có trong danh sách yêu thích.';

        if ($request->expectsJson()) {
            return response()->json(['success' => $deleted, 'message' => $message]);
        }

        return redirect()->back()->with('success', $message);
    }
}
