<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckDiscountTypePercent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->discount_type == 0) {
            if ($request->discount_value > 0 && $request->discount_value <= 100) {
                return $next($request);
            }
            return back()->with('error', 'Discount Value phải lớn hơn 0 và nhỏ hơn 100');
        }
        return $next($request);
    }
}
