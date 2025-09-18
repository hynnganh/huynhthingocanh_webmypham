<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Http\Requests\RegisterUserRequest;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('frontend.login');
    }

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


    public function showRegisterForm()
    {
        return view('frontend.register');
    }


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
    
    public function account()
    {
        $user = Auth::user();
        return view('frontend.account', compact('user'));
    }
    

    public function logout(Request $request)
{
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/')->with('success', 'Đăng xuất thành công');
}

}
