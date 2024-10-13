<?php

namespace App\Http\Middleware;

use App\Models\ProductVariant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckQuantityProductCart
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $id = $request->id;
        $capacity_id = $request->capacity_id;
        $color_id = $request->color_id;
        $quantity = $request->quantity;

        $variants = ProductVariant::with('capacity', 'color', 'product')->where('product_id', $id)->get();
        // dd($variants->toArray());
        foreach ($variants as $item) {
            if ($item->color_id ==  $color_id && $item->capacity_id == $capacity_id) {
                if ($item->quantity > 0 && $item->quantity >= $quantity) {

                    return $next($request);
                } else {
                    return back()->with('error', 'Đã hết hàng hoặc không còn đủ số lượng , Vui lòng chọn lại sản phẩm khác !!!');
                }
            }
        }
    }
}
