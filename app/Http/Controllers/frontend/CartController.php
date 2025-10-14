<?php
namespace App\Http\Controllers\frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
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
    // Kiểm tra xem request có phải là AJAX không
    $isAjax = $request->wantsJson();

    // === 1. KIỂM TRA ĐĂNG NHẬP ===
    if (!Auth::check()) {
        if ($isAjax) {
            // Nếu là AJAX: Trả về lỗi JSON
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để thêm giỏ hàng.',
                'requires_login' => true, // Thêm flag để JS biết phải chuyển hướng
                'redirect_url' => route('login'),
            ], 401); // 401 Unauthorized
        }
        
        // Nếu là Request thông thường: Chuyển hướng
        return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để thêm giỏ hàng.');
    }

    // === 2. XỬ LÝ DỮ LIỆU ===
    $productId = $request->input('id');
    $productName = $request->input('name');
    $productPrice = $request->input('price');
    $quantity = $request->input('quantity', 1);

    // Lấy giỏ hàng từ session
    $cart = session()->get('cart', []);

    // Thêm/Cập nhật sản phẩm
    if (isset($cart[$productId])) {
        $cart[$productId]['quantity'] += $quantity;
    } else {
        $cart[$productId] = [
            "name" => $productName,
            "price" => $productPrice,
            "quantity" => $quantity,
        ];
    }

    // Lưu lại session
    session()->put('cart', $cart);
    
    // Tính toán số lượng item mới trong giỏ hàng (ví dụ: tổng số lượng sản phẩm)
    $newCartCount = array_sum(array_column($cart, 'quantity'));


    // === 3. PHẢN HỒI (QUAN TRỌNG CHO AJAX) ===
    
    if ($isAjax) {
        // Nếu là AJAX: Trả về thành công JSON
        return response()->json([
            'success' => true,
            'message' => 'Thêm vào giỏ hàng thành công!',
            'cart_count' => $newCartCount,
        ]);
    }

    // Nếu là Request thông thường: Tiếp tục chuyển hướng (hoặc dùng back())
    return redirect()->route('cart.index')->with('success', 'Thêm vào giỏ hàng thành công!');
}

    public function update(Request $request)
{
    $cart = session()->get('cart', []);
    $product = \App\Models\Product::find($request->id);

    if (!$product) {
        return redirect()->route('cart.index')->with('error', 'Sản phẩm không tồn tại.');
    }

    $quantity = max(1, min($request->quantity, $product->qty)); // giới hạn từ 1 đến tồn kho

    if (isset($cart[$request->id])) {
        $cart[$request->id]['quantity'] = $quantity;
    }

    session()->put('cart', $cart);
    return redirect()->route('cart.index')->with('success', 'Cập nhật giỏ hàng thành công!');
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

            // Giảm số lượng trong kho
            $product = Product::find($productId);
            if ($product) {
                $product->qty -= $productDetails['quantity'];
                if ($product->qty < 0) $product->qty = 0;
                $product->save();
            }
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

            // Giảm số lượng trong kho ngay cả khi online
            $product = Product::find($productId);
            if ($product) {
                $product->qty -= $productDetails['quantity'];
                if ($product->qty < 0) $product->qty = 0;
                $product->save();
            }
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

    // ========================= MUA NGAY =========================
    public function buyNow(Request $request)
{
    $request->validate([
        'id' => 'required|exists:product,id',
        'quantity' => 'required|integer|min:1',
    ]);

    $product = Product::findOrFail($request->id);
    $quantity = $request->quantity;

    // Kiểm tra tồn kho
    if ($quantity > $product->qty) {
        return redirect()->back()->with('error', "Chỉ còn {$product->qty} sản phẩm '{$product->name}' trong kho");
    }

    // Lấy giỏ hàng hiện tại
    $cart = session()->get('cart', []);

    // Nếu sản phẩm đã có thì cộng dồn nhưng không vượt quá kho
    if(isset($cart[$product->id])) {
        $newQuantity = $cart[$product->id]['quantity'] + $quantity;
        $cart[$product->id]['quantity'] = min($newQuantity, $product->qty);
    } else {
        $cart[$product->id] = [
            "name" => $product->name,
            "quantity" => $quantity,
            "price" => $product->price_sale,
            "thumbnail" => $product->thumbnail
        ];
    }

    // Lưu giỏ hàng
    session()->put('cart', $cart);

    // Chuyển thẳng sang trang checkout
    return redirect()->route('cart.checkout')->with('success', 'Sản phẩm đã được thêm vào giỏ hàng!');
}


}
