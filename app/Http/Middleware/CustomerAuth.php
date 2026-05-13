<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('customer_id')) {
            return redirect()->route('customer.login')->with('error', 'Bạn cần đăng nhập trước!');
        }

        return $next($request);
    }
}