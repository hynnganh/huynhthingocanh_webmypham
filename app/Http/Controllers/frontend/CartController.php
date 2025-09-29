<?php
namespace App\Http\Controllers\frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // ========================= GIỎ HÀNG =========================
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
        $quantity = $request->input('quantity', 1);

        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
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
        if ($request->id && $request->quantity) {
            $cart = session()->get('cart');
            if (isset($cart[$request->id])) {
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
        if (isset($cart[$request->id])) {
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

    // ========================= ĐẶT HÀNG =========================
    // Thanh toán COD
    public function storeOrder(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục.');
        }

        $userId = Auth::id();

        $order = new Order();
        $order->user_id = $userId;
        $order->name = $request->input('name');
        $order->phone = $request->input('phone');
        $order->email = $request->input('email');
        $order->address = $request->input('address');
        $order->note = $request->input('note', '');
        $order->payment_method = 'cod';
        $order->status = 1; // đã thanh toán
        $order->save();

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
        return redirect()->route('cart.index')->with('success', 'Đặt hàng COD thành công!');
    }

    // Thanh toán online (Bank QR)
    public function storeOrderOnline(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Vui lòng đăng nhập'], 401);
        }

        $userId = Auth::id();
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return response()->json(['error' => 'Giỏ hàng trống'], 400);
        }

        $order = new Order();
        $order->user_id = $userId;
        $order->name = $request->input('name');
        $order->phone = $request->input('phone');
        $order->email = $request->input('email');
        $order->address = $request->input('address');
        $order->note = $request->input('note', '');
        $order->payment_method = $request->input('payment_method', 'bank');
        $order->status = 0; // chờ thanh toán
        $order->save();

        foreach ($cart as $productId => $productDetails) {
            $orderDetail = new OrderDetail();
            $orderDetail->order_id = $order->id;
            $orderDetail->product_id = $productId;
            $orderDetail->qty = $productDetails['quantity'];
            $orderDetail->price_buy = $productDetails['price'];
            $orderDetail->amount = $productDetails['quantity'] * $productDetails['price'];
            $orderDetail->save();
        }

        return response()->json([
            'success' => true,
            'order_id' => $order->id
        ]);
    }

    // ========================= QR CODE =========================
    public function getQrCode(Order $order, Request $request)
    {
        $method = $request->query('method', 'bank');

        $amount = (int) $order->orderDetails->sum(fn($item) => $item->qty * $item->price_buy);
        if ($amount <= 0) {
            return response()->json(['error' => 'Số tiền không hợp lệ'], 400);
        }

        if ($method === 'bank') {
            $bankBin = "970422";           // BIDV
            $accountNo = "0869803329";    // số tài khoản BIDV
            $accountName = "HUYNH THI NGOC ANH";
            $info = "Thanh toán đơn hàng #" . $order->id;

            $qrUrl = "https://img.vietqr.io/image/{$bankBin}-{$accountNo}-qr_only.png"
                   . "?amount={$amount}&addInfo=" . urlencode($info);

            return response()->json([
                'url' => $qrUrl,
                'amount' => $amount,
                'account_name' => $accountName,
                'account_no' => $accountNo,
                'bank_bin' => $bankBin,
            ]);
        }

        return response()->json(['error' => 'Phương thức không hỗ trợ'], 400);
    }

    // ========================= XÁC NHẬN THANH TOÁN =========================
    public function confirmPayment(Order $order)
{
    if (!Auth::check() || $order->user_id !== Auth::id()) {
        return response()->json(['error' => 'Bạn không có quyền thực hiện thao tác này'], 403);
    }

    if ($order->status == 1) {
        return response()->json(['error' => 'Đơn hàng đã được thanh toán'], 400);
    }

    $order->status = 1; // đã thanh toán
    $order->save();

    // ✅ Xóa giỏ hàng sau khi xác nhận thanh toán
    session()->forget('cart');

    return response()->json(['success' => true, 'message' => 'Xác nhận thanh toán thành công']);
}
}
