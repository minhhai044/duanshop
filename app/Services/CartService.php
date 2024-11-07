<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;


class CartService
{
    // protected $cartRepository;
    // public function __construct(CartRepository $cartRepository)
    // {
    //     $this->cartRepository = $cartRepository;
    // }
    public function showProductVariantsCart()
    {
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
        } else {
            $productVariants = null;
        }
        return $productVariants;
    }
    public function totalCoupon()
    {
        $productVariants =  $this->showProductVariantsCart();
        $dataCouponsProduct = session('dataCouponsProduct');
        $coupons = session('coupons');
        //Total cart
        $total = 0;
        $subtotal = 0;
        if ($dataCouponsProduct && $coupons) {


            foreach ($productVariants as $item) {
                // dd($item);
                if (!empty($item->product->pro_price_sale)) {

                    foreach ($item->cartitem as $value) {
                        if ($item->cart_id == $value->cart_id) {
                            foreach ($dataCouponsProduct as $CouponsProduct) {
                                if ($item->product->id == $CouponsProduct->product_id) {
                                    if ($coupons['discount_type']) {
                                        $price = ($item->product->pro_price_sale - $coupons['discount_value']) * $value->cart_item_quantity;
                                        $priceSub = $item->product->pro_price_sale * $value->cart_item_quantity;
                                    } else {
                                        $price = ($item->product->pro_price_sale - ($item->product->pro_price_sale * ($coupons['discount_value'] / 100))) * $value->cart_item_quantity;
                                        $priceSub = $item->product->pro_price_sale * $value->cart_item_quantity;
                                    }
                                } else {
                                    foreach ($item->cartitem as $value) {
                                        if ($item->cart_id == $value->cart_id) {
                                            $price = $item->product->pro_price_sale * $value->cart_item_quantity;
                                            $priceSub = $item->product->pro_price_sale * $value->cart_item_quantity;
                                        }
                                    }
                                }
                            }
                        }
                    }
                } else {
                    foreach ($item->cartitem as $value) {
                        if ($item->cart_id == $value->cart_id) {
                            foreach ($dataCouponsProduct as $CouponsProduct) {
                                if ($item->product->id == $CouponsProduct->product_id) {
                                    if ($coupons['discount_type']) {
                                        $price = ($item->product->pro_price_regular - $coupons['discount_value']) * $value->cart_item_quantity;
                                        $priceSub = $item->product->pro_price_regular * $value->cart_item_quantity;
                                    } else {
                                        $price = ($item->product->pro_price_regular - ($item->product->pro_price_regular * ($coupons['discount_value'] / 100))) * $value->cart_item_quantity;
                                        $priceSub = $item->product->pro_price_regular * $value->cart_item_quantity;
                                    }
                                } else {
                                    foreach ($item->cartitem as $value) {
                                        if ($item->cart_id == $value->cart_id) {
                                            $price = $item->product->pro_price_regular * $value->cart_item_quantity;
                                            $priceSub = $item->product->pro_price_regular * $value->cart_item_quantity;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                $total += $price;
                $subtotal += $priceSub;
            }
        } else {
            foreach ($productVariants as $item) {
                if (!empty($item->product->pro_price_sale)) {


                    foreach ($item->cartitem as $value) {
                        if ($item->cart_id == $value->cart_id) {
                            $price = $item->product->pro_price_sale * $value->cart_item_quantity;
                            $priceSub = $item->product->pro_price_sale * $value->cart_item_quantity;
                        }
                    }
                } else {
                    foreach ($item->cartitem as $value) {
                        if ($item->cart_id == $value->cart_id) {
                            $price = $item->product->pro_price_regular * $value->cart_item_quantity;
                            $priceSub = $item->product->pro_price_regular * $value->cart_item_quantity;
                        }
                    }
                }
                $total += $price;
                $subtotal += $priceSub;
            }
        }
        return [
            'total' => $total,
            'subtotal' => $subtotal,
            'dataCouponsProduct' => $dataCouponsProduct,
            'coupons' => $coupons,
        ];
    }
}
