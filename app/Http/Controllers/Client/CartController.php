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
        $user_id = Auth::user()->id;
        $cart = Cart::query()->firstOrCreate(['user_id' => $user_id]);

        $cartCheck = Cart::query()->where('user_id', $user_id)->first();

        $cartItem = CartItem::with('cart')->where([['product_variant_id', $productVariant->id], ['cart_id', $cartCheck->id]])->first();

        try {
            DB::transaction(function () use ($dataCartItem, $request, $cartItem, $cart, $user_id) {
                if (!empty($cartItem) && $cartItem->cart->user_id == $user_id) {
                    $data = [
                        'cart_item_quantity' => $request->quantity + $cartItem->cart_item_quantity
                    ];
                    CartItem::query()->where('id', $cartItem->id)->update($data);
                } else {

                    $dataCartItem['cart_id'] = $cart->id;

                    CartItem::query()->create($dataCartItem);
                }
            });
            return redirect()->route('listcart', $user_id);
        } catch (\Throwable $th) {
            Log::error(__CLASS__ . __FUNCTION__, [$th->getMessage()]);
            return back()->with('error', 'Add to cart không thành công !!!');
        }
    }
    public function listcart()
    {
        try {
            $user_id = Auth::user()->id;

            $cart = Cart::query()->where('user_id', $user_id)->first();

            if (!empty($cart)) {

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



                //Total cart
                $total = 0;
                foreach ($productVariants as $item) {
                    if (!empty($item->product->pro_price_sale)) {


                        foreach ($item->cartitem as $value) {
                            if ($item->cart_id == $value->cart_id) {
                                $price = $item->product->pro_price_sale * $value->cart_item_quantity;
                            }
                        }
                        $total += $price;
                    } else {
                        foreach ($item->cartitem as $value) {
                            if ($item->cart_id == $value->cart_id) {
                                $price = $item->product->pro_price_regular * $value->cart_item_quantity;
                            }
                        }

                        $total += $price;
                    }
                }
            } else {
                $productVariants = null;
            }




            return view('client.cart', compact('productVariants', 'total'));
        } catch (\Throwable $th) {
            return view('client.cart');
        }
    }
    public function cartItemDelete(string $id)
    {
        try {
            $cartItem = CartItem::query()->find($id);
            $cartItem->delete();
            return back()->with('success', 'Thao tác thành công !!!');
        } catch (\Throwable $th) {
            return back()->with('error', 'Thao tác không thành công !!!');
        }
    }
    public function cartitemdeleteall(string $id)
    {
        $data =  CartItem::query()->where('cart_id', $id)->get();
        foreach ($data as $value) {
            $value->delete();
        }
        return redirect()->back()->with('success', 'Thao tác thành công !!!');
    }
}
