<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showAdminLoginForm()
    {
        return view('backend.admin.login');
    }

    public function adminLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Kiểm tra thêm nếu cần phân quyền admin
            if (Auth::user()->roles === 'admin') {
                return redirect()->route('dashboard');
            } else {
                Auth::logout();
                return redirect()->route('admin.login')->with('error', 'Bạn không có quyền truy cập.');
            }
        }

        return redirect()->route('admin.login')->with('error', 'Sai tài khoản hoặc mật khẩu.');
    }

    public function logout(Request $request)
{
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('admin.login.form')->with('success', 'Đã đăng xuất');
}

}

