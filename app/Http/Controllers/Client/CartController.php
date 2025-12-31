<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAddCartRequest;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\ProductVariant;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    protected $cartService;
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }
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

    public function couponsCart(Request $request)
    {
        // Xóa session cũ khi thêm coupon mới
        session()->forget(['dataCouponsProduct', 'coupons']);

        $productVariants = $this->cartService->showProductVariantsCart();

        $code = $request->validate([
            'coupon_code' => 'required'
        ]);

        $coupons = Coupon::with('products')->where('coupon_code', $code)->first();
        $dataCouponsProduct = [];
        if ($coupons) {
            if ($coupons->coupon_status && ($coupons->coupon_used < $coupons->coupon_limit)) {
                foreach ($coupons->products as $value) {
                    foreach ($productVariants as $item) {
                        if ($value->id == $item->product_id) {
                            $dataCouponsProduct[] = $item;
                        }
                    }
                }
                session(['dataCouponsProduct' => $dataCouponsProduct, 'coupons' => $coupons]);
            } else {
                return back()->with('error', 'coupon đã hết hạn !!!');
            }
        } else {
            return back()->with('error', 'coupon không tồn tại !!!');
        }
        return back();
    }

    public function listcart()
    {
        try {

            $productVariants = $this->cartService->showProductVariantsCart();
            $data = $this->cartService->totalCoupon();
            $total = $data['total'];
            $subtotal = $data['subtotal'];
            $dataCouponsProduct = $data['dataCouponsProduct'];
            $coupons = $data['coupons'];
            // session()->forget(['dataCouponsProduct', 'coupons']);

            return view('client.cart', compact('productVariants', 'total', 'dataCouponsProduct', 'coupons', 'subtotal'));
        } catch (\Throwable $th) {
            return view('client.cart');
        }
    }
    public function cartItemDelete(string $id)
    {
        try {
            $cartItem = CartItem::query()->find($id);
            
            $dataCouponsProduct = session()->get('dataCouponsProduct') ?? [];
            $couponss = array_filter($dataCouponsProduct, function ($item) use ($cartItem) {
                return $item['id'] !== $cartItem->product_variant_id;
            });
            $cartItem->delete();

            session(['dataCouponsProduct' => $couponss]);

            return back()->with('success', 'Thao tác thành công !!!');
        } catch (\Throwable $th) {
            return back()->with('error', 'Thao tác không thành công !!!');
        }
    }
    public function cartitemdeleteall(string $id)
    {
        $data =  CartItem::query()->where('cart_id', $id)->get();
        session()->forget(['dataCouponsProduct', 'coupons']);
        foreach ($data as $value) {
            $value->delete();
        }
        return redirect()->back()->with('success', 'Thao tác thành công !!!');
    }
}
