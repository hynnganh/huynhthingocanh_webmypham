<?php
namespace App\Http\Controllers\frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class CartController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để xem giỏ hàng.');
        }
        $cart = session()->get('cart', []);
        return view('frontend.cart.index', compact('cart'));
    }

    public function add(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để thêm giỏ hàng.');
        }
        $productId = $request->input('id');
        $productName = $request->input('name');
        $productPrice = $request->input('price');
        $quantity = $request->input('quantity', 1); // Mặc định số lượng là 1

        $cart = session()->get('cart', []);

        if(isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                "name" => $productName,
                "price" => $productPrice,
                "quantity" => $quantity,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Thêm vào giỏ hàng thành công!');
    }

    public function update(Request $request)
    {
        
        if($request->id && $request->quantity){
            $cart = session()->get('cart');

            if(isset($cart[$request->id])) {
                $cart[$request->id]["quantity"] = $request->quantity;
            }

            session()->put('cart', $cart);

            return redirect()->route('cart.index')->with('success', 'Cập nhật giỏ hàng thành công!');
        }

        return redirect()->route('cart.index')->with('error', 'Có lỗi xảy ra khi cập nhật giỏ hàng!');
    }

    public function remove(Request $request)
    {
        $cart = session()->get('cart');

        if(isset($cart[$request->id])) {
            unset($cart[$request->id]);
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Xóa sản phẩm khỏi giỏ hàng thành công!');
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'Giỏ hàng đã được xóa!');
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn hiện trống!');
        }
        
        return view('frontend.cart.checkout', compact('cart'));
    }

    public function storeOrder(Request $request)
{
    // Kiểm tra người dùng đã đăng nhập chưa
    if (Auth::check()) {
        $userId = Auth::user()->id;
    } else {
        return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục.');
    }

    // Lưu đơn hàng
    $order = new Order();
    $order->user_id = $userId;
    $order->name = $request->input('name');
    $order->phone = $request->input('phone');
    $order->email = $request->input('email');
    $order->address = $request->input('address');
    $order->note = $request->input('note', ''); 
    $order->status = 1; // Đơn hàng đã được thanh toán

    $order->save();

    // Lưu chi tiết đơn hàng
    $cart = session()->get('cart', []);
    foreach ($cart as $productId => $productDetails) {
        $orderDetail = new OrderDetail();
        $orderDetail->order_id = $order->id;
        $orderDetail->product_id = $productId;
        $orderDetail->qty = $productDetails['quantity'];
        $orderDetail->price_buy = $productDetails['price'];
        $orderDetail->amount = $productDetails['quantity'] * $productDetails['price'];
        $orderDetail->save();
    }

    session()->forget('cart');

    return redirect()->route('cart.index')->with('success', 'Đặt hàng thành công!');
}

}
