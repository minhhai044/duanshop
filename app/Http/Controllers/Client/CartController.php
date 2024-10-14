<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAddCartRequest;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $cart = Cart::query()->where('user_id', $request->user_id)->first();

        $cartItem = CartItem::with('cart')->where([['product_variant_id', $productVariant->id], ['cart_id', $cart->id]])->first();

        try {
            DB::transaction(function () use ($dataCartItem, $request, $cartItem) {
                if (!empty($cartItem) && $cartItem->cart->user_id == $request->user_id) {
                    $data = [
                        'cart_item_quantity' => $request->quantity + $cartItem->cart_item_quantity
                    ];
                    CartItem::query()->where('id', $cartItem->id)->update($data);
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
    public function listcart()
    {
        $user_id = Auth::user()->id;

        $cart = Cart::query()->where('user_id', $user_id)->first();


        $cartItem = CartItem::query()->where('cart_id', $cart->id)->get();
        // dd($cartItem);
        $productVariants = [];

        foreach ($cartItem as $item) {
            $productVariant = ProductVariant::with(
                'capacity',
                'color',
                'product',
                'cartitem'
            )->where('id', $item->product_variant_id)->first();
            $productVariant->cart_id = $item->cart_id;
            $productVariants[] = $productVariant;
        }

        // dd($productVariants);

        return view('client.cart', compact('productVariants'));
    }
    public function cartItemDelete(string $id) {
        try {
            $cartItem = CartItem::query()->find($id);
            $cartItem->delete();
            return back()->with('success','Thao tác thành công !!!');
        } catch (\Throwable $th) {
            return back()->with('error','Thao tác không thành công !!!');
        }
    }
}
