<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckOutRequest;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function checkout()
    {
        $user = Auth::user();
        $cart = Cart::query()->where('user_id', $user->id)->first();
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
        $total = 0;
        foreach ($productVariants as $item) {
            if (!empty($item->product->pro_price_sale)) {
                foreach ($item->cartitem as $value) {
                    if ($item->cart_id == $value->cart_id) {
                        $price = $value->cart_item_quantity * $item->product->pro_price_sale;
                    }
                }
                $total += $price;
            } else {
                foreach ($item->cartitem as $value) {
                    if ($item->cart_id == $value->cart_id) {
                        $price = $value->cart_item_quantity * $item->product->pro_price_regular;
                    }
                }
                $total += $price;
            }
        }


        return view('client.checkout', compact('productVariants', 'total'));
    }

    public function storeCheckout(CheckOutRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $user = Auth::user();
                $data = $request->all();
                $data['user_id'] = $user->id;
                $order = Order::query()->create($data);


                $cart = Cart::query()->where('user_id', $user->id)->first();
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
                        if ($value->cart_id == $item->cart_id) {
                            $quantityCart = $value->cart_item_quantity;
                            $dataItem = [
                                'order_id' => $order->id,
                                'product_variant_id' => $item->id,
                                'order_item_quantity' => $value->cart_item_quantity,
                                'product_name' => $item->product->pro_name,
                                'product_sku' => $item->product->pro_sku,
                                'product_img_thumbnail' => $item->product->pro_img_thumbnail,
                                'pro_price_regular' => $item->product->pro_price_regular,
                                'pro_price_sale' => $item->product->pro_price_sale,
                                'variant_capacity_name' => $item->capacity->cap_name,
                                'variant_color_name' => $item->color->color_name,

                            ];
                        }
                    }
                    OrderItem::query()->create($dataItem);
                    $data = [
                        'quantity' => $item->quantity - $quantityCart
                    ];
                    ProductVariant::query()->where('id', $item->id)->update($data);
                }
                CartItem::query()->where('cart_id', $cart->id)->delete();
                $cart->delete();
            });
            return redirect()->route('thankyou');
        } catch (\Throwable $th) {
            Log::debug(__CLASS__ . '@' . __FUNCTION__, [$th->getMessage()]);
            return back();
        }
    }
    public function listorders()
    {
        $listOrders  = Order::with('orderItems', 'user')->where('user_id', Auth::user()->id)->latest('id')->paginate(5);
        return view('client.order', compact('listOrders'));
    }
    public function showOrders(string $id)
    {
        $data = Order::with('orderItems', 'user')->findOrFail($id);

        return view('client.show', compact('data'));
    }
    public function ordersCancel(Request $request, string $id)
    {
        try {
            DB::transaction(function () use ($request, $id) {
                $order = Order::with('orderItems', 'user')->findOrFail($id);
                if ($request->status_order === STATUS_ORDER_PENDING) {
                    $order->update([
                        'status_order' => STATUS_ORDER_CANCELED
                    ]);
                }
                $productVarriant = ProductVariant::query()->get();
                foreach ($productVarriant as $item) {
                    foreach ($order->orderItems as $value) {
                        if ($value->product_variant_id == $item->id) {
                            $item->update([
                                'quantity' => $item->quantity + $value->order_item_quantity
                            ]);
                        }
                    }
                }
            });
            return redirect()->route('listorders')->with('success', 'Hủy thành công !!!');
        } catch (\Throwable $th) {
            return back()->with('error', 'Hủy không thành công !!!');
        }
    }
}
