<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Str;
use App\Http\Requests\RegisterUserRequest;
use App\Models\OrderDetail;


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
        $user = new User();
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->address = $request->address;
        $user->username = $request->username;
        $user->roles = 'customer';
        $user->password = Hash::make($request->password);

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $extension = $file->getClientOriginalExtension();
            $filename = Str::slug($request->username) . '-' . time() . '.' . $extension;
            $file->move(public_path('assets/images/user'), $filename);
            $user->avatar = $filename;
        } else {
            $user->avatar = 'default.png';
        }

        $user->created_by = Auth::id() ?? 1;
        $user->status = 1;
        $user->save();

        Auth::login($user);
        return redirect()->route('login')->with('success', 'Đăng ký thành công');
    }

    // Trang account + liệt kê đơn hàng
    // Trang danh sách đơn hàng
public function account()
{
    $user = Auth::user();

    $orders = Order::where('user_id', $user->id)
                   ->orderBy('created_at', 'desc')
                   ->get();

    return view('frontend.account', compact('user', 'orders'));
}

// Chi tiết đơn hàng
// Chi tiết đơn hàng frontend
public function orderDetail($id)
{
    $user = Auth::user();

    // Lấy đơn hàng của user đang đăng nhập, cùng chi tiết sản phẩm
    $order = Order::with('orderDetails.product')
                  ->where('id', $id)
                  ->where('user_id', $user->id)
                  ->firstOrFail();

    // Chuyển đổi dữ liệu chi tiết đơn hàng
    $orderDetails = $order->orderDetails->map(function($detail) {
        return [
            'product_name'  => $detail->product->name,
            'product_image' => $detail->product->thumbnail ?? 'default.png',
            'price'         => $detail->price_buy,
            'quantity'      => $detail->qty,
            'total'         => $detail->price_buy, // nếu muốn tính tổng = price_buy * qty thì đổi ở đây
        ];
    });

    return view('frontend.order-detail', compact('order', 'orderDetails'));
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

    // Xử lý gửi OTP (giả lập)
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

        // Giả lập gửi mail: lưu OTP vào session
        session()->flash('otp_code', $code);
        session()->flash('reset_email', $request->email);

        // Redirect sang form reset password
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
}
