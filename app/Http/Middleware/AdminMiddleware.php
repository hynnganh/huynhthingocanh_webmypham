<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Kiểm tra nếu chưa đăng nhập bằng guard admin
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login.form')
                ->with('error', 'Vui lòng đăng nhập để vào trang quản trị.');
        }

        // Kiểm tra nếu không có quyền admin
        if (Auth::guard('admin')->user()->roles !== 'admin') {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login.form')
                ->with('error', 'Tài khoản của bạn không có quyền truy cập.');
        }

        return $next($request);
    }
}
