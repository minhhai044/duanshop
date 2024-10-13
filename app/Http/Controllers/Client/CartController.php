<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAddCartRequest;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function addcart(StoreAddCartRequest $request)
    {

        $productVariant = ProductVariant::with(['capacity', 'color'])->where([
            'color_id' => $request->color_id,
            'capacity_id' => $request->capacity_id,
            'product_id' => $request->id
        ])->first();

        $dataCartItem = [
            'product_variant_id' => $productVariant->id,
            'cart_item_quantity' => $request->quantity
        ];

        $cartItem = CartItem::with('cart')->where('product_variant_id', $productVariant->id)->first();
        try {
            DB::transaction(function () use ($dataCartItem, $request, $cartItem) {
                if ($cartItem && $cartItem->cart->user_id == $request->user_id) {
                    $data = [
                        'cart_item_quantity' => $request->quantity + $cartItem->cart_item_quantity
                    ];
                    CartItem::query()->update($data);
                } else {
                    $cart = Cart::query()->firstOrCreate(['user_id' => $request->user_id]);
                    $dataCartItem['cart_id'] = $cart->id;

                    CartItem::query()->create($dataCartItem);
                }
            });
            return redirect()->route('listcart', $request->user_id);
        } catch (\Throwable $th) {
            Log::error(__CLASS__ . __FUNCTION__, [$th->getMessage()]);
            return back()->with('error', 'Add to cart không thành công !!!');
        }



        // if ( !isset(session('cart')[$productVariant->id]) ) {
        //     $data = $product->toArray() + $productVariant->toArray() + ['quantity_cart' => $request->quantity];
        //     session()->put('cart.' . $productVariant->id , $data);
        // }else{
        //     $data = session('cart')[$productVariant->id];
        //     $data['quantity_cart'] = $request->quantity;
        //     session()->put('cart.' . $productVariant->id , $data);
        // }

        // return redirect()->route('listcart');
    }
    public function listcart(string $id)
    {
        $user = Cart::query()->where('user_id', $id)->first();
        $cartItem = CartItem::with(['cart', 'productVariant'])->where('cart_id', $user->id)->get();
        // dd($cartItem);
        $productVariant = [];
        
        foreach ($cartItem as $item) {
            $productVariant[] = ProductVariant::with(
                'capacity',
                'color',
                'product',
                'cartitem'
            )->where('id', $item->product_variant_id)->first();
        }
        

        return view('client.cart', compact('productVariant'));
    }
}
