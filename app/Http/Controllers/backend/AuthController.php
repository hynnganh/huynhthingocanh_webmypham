<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Hiển thị form đăng nhập admin
     */
    public function showAdminLoginForm()
    {
        return view('backend.admin.login');
    }

    /**
     * Xử lý đăng nhập admin
     */
    public function adminLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Dùng guard 'admin' để đăng nhập riêng
        if (Auth::guard('admin')->attempt($credentials)) {
            $user = Auth::guard('admin')->user();

            if ($user->roles === 'admin') {
                return redirect()->route('dashboard')->with('success', 'Đăng nhập thành công!');
            } else {
                Auth::guard('admin')->logout();
                return redirect()->route('admin.login.form')->with('error', 'Bạn không có quyền truy cập.');
            }
        }

        return redirect()->route('admin.login.form')->with('error', 'Sai tài khoản hoặc mật khẩu.');
    }

    /**
     * Đăng xuất admin
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login.form')->with('success', 'Đã đăng xuất.');
    }
}
