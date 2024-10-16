<?php

namespace App\Http\Middleware;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckQuantityCheckOutCart
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        $cart = Cart::query()->where('user_id', $user->id)->first();
        if (!empty($cart)) {
            $cartItem = CartItem::query()->where('cart_id', $cart->id)->get();
            $productVariants = [];
            foreach ($cartItem as $item) {
                $productVariant = ProductVariant::with(
                    'capacity',
                    'color',
                    'product',
                    'cartitem'
                )->find($item->product_variant_id);
                $productVariant->cart_id = $item->cart_id;
                $productVariants[] = $productVariant;
            }

            foreach ($productVariants as $item) {
                foreach ($item->cartitem as $value) {
                    if ($item->quantity >= $value->cart_item_quantity) {
                        return $next($request);
                    } else {
                        return redirect()->route('listcart')->with('error', 'Không còn đủ số lượng sản phẩm !!!');
                    }
                }
            }
        } else {
            abort(404);
        }
    }
}
