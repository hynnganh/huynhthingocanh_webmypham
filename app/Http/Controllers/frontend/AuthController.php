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

    // Lấy đơn hàng của user, luôn là Collection
    $orders = Order::with('orderDetails.product')
                   ->where('user_id', $user->id)
                   ->orderBy('created_at', 'desc')
                   ->get() ?? collect();

    // Load reviews của user (nếu chưa load)
    $user->load(['reviews.product']);

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

    // Tạo mảng trạng thái kèm màu sắc
    $statusLabels = [
        1 => ['text' => 'Chờ xác nhận', 'color' => 'yellow-600'],
        2 => ['text' => 'Đã xác nhận', 'color' => 'blue-600'],
        3 => ['text' => 'Đang chuẩn bị hàng', 'color' => 'orange-600'],
        4 => ['text' => 'Đang giao hàng', 'color' => 'green-600'],
        5 => ['text' => 'Giao thành công', 'color' => 'teal-600'],
    ];

    $status = $statusLabels[$order->status] ?? ['text' => 'Chưa xác định', 'color' => 'gray-500'];

    return view('frontend.order-detail', compact('order', 'orderDetails', 'status'));
}


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


public function update(Request $request, $id)
{
    // Tìm người dùng theo ID
    $user = User::findOrFail($id);

    // Cập nhật thông tin cơ bản
    $user->name = $request->name;
    $user->phone = $request->phone;
    $user->email = $request->email;
    $user->address = $request->address;
    $user->username = $request->username;

    // Nếu có nhập mật khẩu mới thì cập nhật
    if (!empty($request->password)) {
        $user->password = Hash::make($request->password);
    }

    // Xử lý avatar (nếu có upload mới)
    if ($request->hasFile('avatar')) {
        // Xóa ảnh cũ (trừ khi là ảnh mặc định)
        if ($user->avatar && $user->avatar !== 'default.png') {
            $oldPath = public_path('assets/images/user/' . $user->avatar);
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        $file = $request->file('avatar');
        $extension = $file->getClientOriginalExtension();
        $filename = Str::slug($request->username) . '-' . time() . '.' . $extension;
        $file->move(public_path('assets/images/user'), $filename);
        $user->avatar = $filename;
    }

    // Cập nhật thông tin người sửa
    $user->updated_by = Auth::id() ?? 1;
    $user->updated_at = now();

    // Lưu lại thay đổi
    $user->save();

    return redirect()->back()->with('success', 'Cập nhật thông tin thành công');
}
}