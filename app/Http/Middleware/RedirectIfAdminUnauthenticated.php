<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class RedirectIfAdminUnauthenticated extends Middleware
{
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            // Check if the request is trying to access an admin-protected page
            // and the user is NOT authenticated with the 'admin' guard
            if (! Auth::guard('admin')->check()) {
                return route('admin.login.form'); 
            }
        }
    }
}