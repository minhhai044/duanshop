<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CouponCheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->start_date && $request->end_date) {
            if ($request->start_date < $request->end_date) {
                return $next($request);
            }
            return back()->with('error', 'Start Date không được muộn hơn End Date !!!');
        }
        return $next($request);
    }
}
