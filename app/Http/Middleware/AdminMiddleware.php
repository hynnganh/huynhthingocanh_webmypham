<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Nếu chưa đăng nhập
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login.form')
                ->with('error', 'Vui lòng đăng nhập để vào trang quản trị.');
        }

        // Nếu không phải admin
        if (Auth::guard('admin')->user()->roles !== 'admin') {
            Auth::guard('admin')->logout();
            return redirect()->route('login')
                ->with('error', 'Bạn không có quyền truy cập trang quản trị.');
        }

        // Cho phép đi tiếp nếu là admin
        return $next($request);
    }
}
