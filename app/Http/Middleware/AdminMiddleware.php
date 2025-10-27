<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Nếu chưa đăng nhập → chuyển về trang đăng nhập admin
        if (!Auth::check()) {
            return redirect()->route('admin.login.form')
                ->with('error', 'Vui lòng đăng nhập để vào trang quản trị.');
        }

        // Nếu không phải admin → chặn
        if (Auth::user()->roles !== 'admin') {
            Auth::logout();
            return redirect()->route('admin.login.form')
                ->with('error', 'Tài khoản của bạn không có quyền truy cập.');
        }

        return $next($request);
    }
}
