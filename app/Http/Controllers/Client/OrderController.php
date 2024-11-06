<?php

namespace App\Http\Controllers\Client;

use App\Events\SendMailOrderEvent;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Mail\OrderMailController;
use App\Http\Requests\CheckOutRequest;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    protected $cartService;
    public function __construct(
        CartService $cartService
    ) {
        $this->cartService = $cartService;
    }
    public function checkout()
    {
        try {

            $productVariants = $this->cartService->showProductVariantsCart();
            $data = $this->cartService->totalCoupon();
            $total = $data['total'];
            $subtotal = $data['subtotal'];
            $dataCouponsProduct = $data['dataCouponsProduct'];
            $coupons = $data['coupons'];


            return view('client.checkout', compact('productVariants', 'total', 'dataCouponsProduct', 'coupons', 'subtotal'));
        } catch (\Throwable $th) {
            return view('client.checkout');
        }
    }

    public function storeCheckout(CheckOutRequest $request)
    {
        try {
            $order = null;
            DB::transaction(function () use ($request, &$order) {
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
                $Coupon = Coupon::query()->find(session('coupons')['id']);
                $checkSS = $Coupon->update([
                    'coupon_used' => $Coupon->coupon_used + 1
                ]);
                if ($checkSS) {
                    session()->forget(['dataCouponsProduct', 'coupons']);
                }
            });
            return redirect()->route('thankyou', $order);
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
