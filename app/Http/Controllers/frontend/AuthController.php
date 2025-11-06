<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Support\Str;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\UpdateUserRequest;

class AuthController extends Controller
{
    // Hiển thị form login
    public function showLoginForm()
    {
        return view('frontend.login');
    }

    // Xử lý login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/')->with('success', 'Đăng nhập thành công');
        }

        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không đúng',
        ])->withInput();
    }

    // Hiển thị form đăng ký
    public function showRegisterForm()
    {
        return view('frontend.register');
    }

    // Xử lý đăng ký
    public function register(RegisterUserRequest $request)
    {
        // Tạo người dùng mới
        $user = new User();
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->address = $request->address;
        $user->username = $request->username;
        $user->roles = 'customer'; // Gán mặc định
        $user->password = Hash::make($request->password);
    
        // Xử lý avatar (nếu có)
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $extension = $file->getClientOriginalExtension();
            $filename = Str::slug($request->username) . '-' . time() . '.' . $extension;
            $file->move(public_path('assets/images/user'), $filename);
            $user->avatar = $filename;
        } else {
            $user->avatar = 'default.png'; // Đặt ảnh mặc định nếu không có file
        }
    
        // Gán created_by và thời gian
        $user->created_by = Auth::id() ?? 1;
        $user->created_at = now();
        $user->status = 1;
        $user->save();
    
        // Đăng nhập và chuyển hướng
        Auth::login($user);
        return redirect()->route('login')->with('success', 'Đăng ký thành công');
    }

    // Trang account + liệt kê đơn hàng
    public function account()
{
    $user = Auth::user();

    // Lấy đơn hàng của user, luôn là Collection, kèm theo orderDetails và product
    $orders = Order::with('orderDetails.product')
                    ->where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->get() ?? collect();

    // Lấy tất cả product_id từ các orderDetails của user
    $productIds = $orders->flatMap(function($order){
        return $order->orderDetails->pluck('product_id');
    });

    // Kiểm tra xem user đã review sản phẩm nào chưa
    $orders->each(function($order) use ($user) {
    $order->reviewed = $order->orderDetails->every(function($item) use ($user) {
        return \App\Models\ProductReview::where('user_id', $user->id)
                    ->where('product_id', $item->product_id)
                    ->exists();
    });
});


    return view('frontend.account', compact('user', 'orders'));
}


    // Chi tiết đơn hàng
    public function orderDetail($id)
    {
        $user = Auth::user();

        // Lấy đơn hàng của user, cùng chi tiết sản phẩm
        $order = Order::with('orderDetails.product')
                        ->where('id', $id)
                        ->where('user_id', $user->id)
                        ->firstOrFail();

        // Chuyển đổi dữ liệu chi tiết đơn hàng
        $orderDetails = $order->orderDetails->map(function($detail) {
            return [
                'product_name'  => $detail->product->name ?? 'Sản phẩm đã xóa',
                'product_image' => $detail->product->thumbnail ?? 'default.png',
                'price'         => $detail->price_buy,
                'quantity'      => $detail->qty,
                'total'         => $detail->price_buy * $detail->qty,
            ];
        });

            $hasReviewed = \App\Models\ProductReview::where('user_id', $user->id)
                    ->whereIn('product_id', $order->orderDetails->pluck('product_id'))
                    ->exists();
        // Tạo mảng trạng thái kèm màu sắc
        $statusLabels = [
            1 => ['text' => 'Chờ xác nhận', 'color' => 'yellow-600'],
            2 => ['text' => 'Đang chuẩn bị hàng', 'color' => 'orange-600'],
            3 => ['text' => 'Đang giao hàng', 'color' => 'green-600'],
            4 => ['text' => 'Giao thành công', 'color' => 'teal-600'],
            5 => ['text' => 'Đã hủy', 'color' => 'red-600'],
        ];

        $status = $statusLabels[$order->status] ?? ['text' => 'Chưa xác định', 'color' => 'gray-500'];

 return view('frontend.order-detail', compact('order', 'orderDetails', 'status', 'hasReviewed'));    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Đăng xuất thành công');
    }

    // Hiển thị form quên mật khẩu
    public function showForgotPasswordForm()
    {
        return view('frontend.forgot-password');
    }

    // Gửi OTP (giả lập)
    public function sendResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:user,email'
        ]);

        $user = User::where('email', $request->email)->first();
        $code = rand(100000, 999999);
        $user->reset_code = $code;
        $user->reset_code_expire = now()->addMinutes(10);
        $user->save();

        session()->flash('otp_code', $code);
        session()->flash('reset_email', $request->email);

        return redirect()->route('password.reset')
                        ->with('success', "Mã OTP đã gửi (giả lập): $code");
    }

    // Hiển thị form reset password
    public function showResetPasswordForm()
    {
        return view('frontend.reset-password');
    }

    // Xử lý reset password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:user,email',
            'code' => 'required',
            'password' => 'required|confirmed|min:6'
        ]);

        $user = User::where('email', $request->email)
                        ->where('reset_code', $request->code)
                        ->first();

        if (!$user) {
            return back()->withErrors(['code' => 'Mã xác thực không hợp lệ']);
        }

        if ($user->reset_code_expire < now()) {
            return back()->withErrors(['code' => 'Mã xác thực đã hết hạn']);
        }

        $user->password = Hash::make($request->password);
        $user->reset_code = null;
        $user->reset_code_expire = null;
        $user->save();

        return redirect()->route('login')->with('success', 'Mật khẩu đã được đặt lại thành công');
    }

    public function resendOtp(Request $request)
{
    // 1. Xác thực Email
    $request->validate([
        // Đảm bảo tên bảng là 'users' nếu bạn đang dùng Laravel tiêu chuẩn
        'email' => 'required|email|exists:user,email' 
    ]);

    $user = User::where('email', $request->email)->first();

    // 2. Tạo mã OTP mới và lưu vào DB
    $code = rand(100000, 999999);
    
    // Lưu mã OTP mới và thời hạn (10 phút)
    $user->reset_code = $code;
    $user->reset_code_expire = now()->addMinutes(10); 
    $user->save();

    // 3. Gửi mã OTP (Giả lập)
    
    // Thay thế dòng này bằng logic gửi email thực tế (Mail::to()->send(new OTPMail($code)))
    \Log::info("Mã OTP mới cho {$request->email} (Gửi lại): {$code}"); 
    
    // Lưu mã vào session *chỉ để debug/giả lập* trong quá trình phát triển
    session()->flash('otp_code', $code); 

    // 4. Trả về phản hồi JSON cho yêu cầu AJAX
    return response()->json([
        'success' => true,
        'message' => 'Mã xác thực mới đã được gửi thành công đến email của bạn.',
        'debug_code' => $code // Chỉ nên có khi đang debug
    ], 200);
}
    /**
     * CẬP NHẬT: Trả về JSON cho AJAX.
     */
public function update(Request $request)
{
    // Lấy user
    $user = Auth::user();
    if (!$user) {
        return redirect()->route('account')->with('error', 'Người dùng không tồn tại!');
    }

    // Validate trực tiếp
    $request->validate([
        'name' => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:user,username,' . $user->id,
        'phone' => 'nullable|regex:/^[0-9\+]+$/|max:20', // Chỉ cho phép số và dấu +
        'address' => 'nullable|string|max:255',
        'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ], [
        'name.required' => 'Tên không được để trống.',
        'username.required' => 'Username không được để trống.',
        'username.unique' => 'Username đã tồn tại.',
        'phone.regex' => 'Số điện thoại chỉ được nhập số và dấu +.',
        'avatar.image' => 'Avatar phải là file hình ảnh.',
        'avatar.mimes' => 'Avatar phải có định dạng: jpeg, png, jpg, gif.',
        'avatar.max' => 'Avatar dung lượng tối đa 2MB.',
    ]);

    // Cập nhật thông tin
    $user->name = $request->name;
    $user->username = $request->username;
    $user->phone = $request->phone;
    $user->address = $request->address;

    // Xử lý avatar
    if ($request->hasFile('avatar')) {
        $file = $request->file('avatar');
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('assets/images/user'), $filename);

        // Xóa avatar cũ nếu không phải default.png
        if ($user->avatar && $user->avatar != 'default.png' && file_exists(public_path('assets/images/user/' . $user->avatar))) {
            unlink(public_path('assets/images/user/' . $user->avatar));
        }

        $user->avatar = $filename;
    }

    $user->save();

    return redirect()->route('account')->with('success', 'Cập nhật thông tin thành công!');
}

public function review($id)
{
    $order = Order::with('orderDetails.product')->findOrFail($id);

    // Chỉ cho phép review nếu đơn đã giao thành công
    if ($order->status != 4) {
        return redirect()->back()->with('error', 'Chỉ có thể đánh giá sau khi đơn hàng giao thành công.');
    }

    return view('frontend.order.review', compact('order'));
}

public function submitReview(Request $request, $id)
{
    $order = Order::with('orderDetails.product')->findOrFail($id);

    foreach ($order->orderDetails as $item) {
        $rating = $request->input("rating_{$item->product_id}");
        $comment = $request->input("comment_{$item->product_id}");

        // Nếu user đã từng đánh giá sản phẩm này thì bỏ qua
        $existingReview = \App\Models\ProductReview::where('product_id', $item->product_id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existingReview) {
            continue;
        }

        // Xử lý ảnh (nếu có)
        $imagePath = null;
        if ($request->hasFile("image_{$item->product_id}")) {
            $file = $request->file("image_{$item->product_id}");
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/images/reviews'), $filename);
            $imagePath = 'assets/images/reviews/' . $filename;
        }

        if ($rating) {
            \App\Models\ProductReview::create([
                'product_id' => $item->product_id,
                'user_id' => auth()->id(),
                'rating' => $rating,
                'comment' => $comment,
                'image' => $imagePath, // lưu ảnh
            ]);
        }
    }

    return redirect()->route('account')->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm!');
}


public function returnOrder($id)
{
    $order = Order::with('orderDetails.product')->findOrFail($id);

    // Chỉ cho phép trả hàng nếu đơn giao thành công
    if ($order->status != 4) {
        return redirect()->back()->with('error', 'Chỉ có thể yêu cầu trả hàng sau khi đơn hàng giao thành công.');
    }

    return view('frontend.order.return', compact('order'));
}

public function submitReturn(Request $request, $id)
{
    $request->validate([
        'reason' => 'required|string|max:500',
        'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $order = Order::findOrFail($id);

    // Lưu hình minh chứng (nếu có)
    $filename = null;
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('assets/images/returns'), $filename);
    }

    // Cập nhật trạng thái đơn hàng thành "6" (đã hủy / trả hàng)
    $order->status = 6;
    $order->save();

    // Lưu lý do trả hàng (nếu Ngọc Ánh chưa tạo bảng riêng thì chỉ cần lưu tạm trong cột note)
    $order->note = 'Trả hàng: ' . $request->reason;
    $order->save();

    // Nếu có bảng `order_returns` thì có thể thêm:
    // OrderReturn::create([
    //     'order_id' => $order->id,
    //     'user_id' => auth()->id(),
    //     'reason' => $request->reason,
    //     'image' => $filename,
    //     'status' => 'Đã yêu cầu trả hàng',
    // ]);

    return redirect()->route('account')->with('success', 'Yêu cầu trả hàng đã được gửi, đơn hàng đã chuyển sang trạng thái "Đã hủy / Trả hàng".');
}

public function viewReview($id)
{
    $order = Order::with('orderDetails.product')->findOrFail($id);

    // Lấy danh sách đánh giá của user cho các sản phẩm trong đơn này
    $reviews = \App\Models\ProductReview::where('user_id', auth()->id())
                ->whereIn('product_id', $order->orderDetails->pluck('product_id'))
                ->get()
                ->keyBy('product_id'); // để dễ tra theo product_id trong view

    return view('frontend.order.view-review', compact('order', 'reviews'));
}
public function cancel(Request $request, $id)
{
    $order = Order::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

    if($order->status != 1){
        return redirect()->back()->with('error', 'Đơn hàng không thể hủy ở trạng thái hiện tại.');
    }

    $request->validate([
        'cancel_note' => 'required|string|max:500',
    ]);

    $order->status = 5; // Đã hủy
    $order->note = $request->cancel_note; // Ghi lý do hủy
    $order->save();

    return redirect()->back()->with('success', 'Đơn hàng đã hủy thành công.');
}


}