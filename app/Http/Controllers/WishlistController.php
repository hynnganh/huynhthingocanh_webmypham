<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlist = Wishlist::where('user_id', Auth::id())
            ->with('product')
            ->get();

        return view('wishlist.index', compact('wishlist'));
    }

    public function add(Request $request)
    {
        $productId = $request->product_id;

        Wishlist::firstOrCreate([
            'user_id' => Auth::id(),
            'product_id' => $productId
        ]);

        return response()->json(['success' => true, 'message' => 'Đã thêm vào danh sách yêu thích!']);
    }

    public function remove(Request $request)
    {
        Wishlist::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->delete();

        return response()->json(['success' => true, 'message' => 'Đã xóa khỏi danh sách yêu thích!']);
    }
}
