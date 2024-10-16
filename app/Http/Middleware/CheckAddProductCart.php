<?php

namespace App\Http\Middleware;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAddProductCart
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $productVariant = ProductVariant::query()->where([
            ['capacity_id', $request->capacity_id],
            ['color_id', $request->color_id],
            ['product_id', $request->id],
        ])->first();
        $user_id = Auth::user()->id;
        $cart = Cart::query()->where('user_id', $user_id)->first();

        if ($cart) {
            $cartItem = CartItem::query()->where([
                ['product_variant_id', $productVariant->id],
                ['cart_id', $cart->id]
            ])->first();
        }


        if (empty($cartItem)) {
            $quantityCartItem = 0;
        } else {
            $quantityCartItem = $cartItem->cart_item_quantity;
        }
        $inventory = $productVariant->quantity - $quantityCartItem;
        if ($request->quantity > $inventory) {
            return back()->with('error', 'Đã hết hàng hoặc không còn đủ số lượng , Vui lòng chọn lại sản phẩm khác !!!');
        } else {
            return $next($request);
        }
        
    }
}
