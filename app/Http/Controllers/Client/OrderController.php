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
use App\Models\VnpayPayment;
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



                if (!empty(session('coupons'))) {
                    $Coupon = Coupon::query()->find(session('coupons')['id']);
                    $checkSS = $Coupon->update([
                        'coupon_used' => $Coupon->coupon_used + 1
                    ]);
                    if ($checkSS) {
                        session()->forget(['dataCouponsProduct', 'coupons']);
                    }
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

    public function paymentVnpay(Request $request)
    {
        // dd($request);
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = "https://duanshop.test/$request->order_id/thankyoupayment";
        $vnp_TmnCode = "CW3MWMKN"; //Mã website tại VNPAY 
        $vnp_HashSecret = "2EQ9DCNFBR3H0GRQ4RCVHYTO1VZYXFLZ"; //Chuỗi bí mật

        $vnp_TxnRef = $request->order_sku; //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này  sang VNPAY
        $vnp_OrderInfo = "Thanh toán Vnpay";
        $vnp_OrderType = "Thanh toán hóa đơn";
        $vnp_Amount = $request->order_total_price * 100;
        $vnp_Locale = 'vn';
        $vnp_BankCode = 'NCB';
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
        //Add Params of 2.0.1 Version
        // $vnp_ExpireDate = $_POST['txtexpire'];
        //Billing
        // $vnp_Bill_Mobile = $_POST['txt_billing_mobile'];
        // $vnp_Bill_Email = $_POST['txt_billing_email'];
        // $fullName = trim($_POST['txt_billing_fullname']);
        // if (isset($fullName) && trim($fullName) != '') {
        //     $name = explode(' ', $fullName);
        //     $vnp_Bill_FirstName = array_shift($name);
        //     $vnp_Bill_LastName = array_pop($name);
        // }
        // $vnp_Bill_Address = $_POST['txt_inv_addr1'];
        // $vnp_Bill_City = $_POST['txt_bill_city'];
        // $vnp_Bill_Country = $_POST['txt_bill_country'];
        // $vnp_Bill_State = $_POST['txt_bill_state'];
        // // Invoice
        // $vnp_Inv_Phone = $_POST['txt_inv_mobile'];
        // $vnp_Inv_Email = $_POST['txt_inv_email'];
        // $vnp_Inv_Customer = $_POST['txt_inv_customer'];
        // $vnp_Inv_Address = $_POST['txt_inv_addr1'];
        // $vnp_Inv_Company = $_POST['txt_inv_company'];
        // $vnp_Inv_Taxcode = $_POST['txt_inv_taxcode'];
        // $vnp_Inv_Type = $_POST['cbo_inv_type'];
        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef
            // "vnp_ExpireDate" => $vnp_ExpireDate,
            // "vnp_Bill_Mobile" => $vnp_Bill_Mobile,
            // "vnp_Bill_Email" => $vnp_Bill_Email,
            // "vnp_Bill_FirstName" => $vnp_Bill_FirstName,
            // "vnp_Bill_LastName" => $vnp_Bill_LastName,
            // "vnp_Bill_Address" => $vnp_Bill_Address,
            // "vnp_Bill_City" => $vnp_Bill_City,
            // "vnp_Bill_Country" => $vnp_Bill_Country,
            // "vnp_Inv_Phone" => $vnp_Inv_Phone,
            // "vnp_Inv_Email" => $vnp_Inv_Email,
            // "vnp_Inv_Customer" => $vnp_Inv_Customer,
            // "vnp_Inv_Address" => $vnp_Inv_Address,
            // "vnp_Inv_Company" => $vnp_Inv_Company,
            // "vnp_Inv_Taxcode" => $vnp_Inv_Taxcode,
            // "vnp_Inv_Type" => $vnp_Inv_Type
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        // if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
        //     $inputData['vnp_Bill_State'] = $vnp_Bill_State;
        // }

        //var_dump($inputData);
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //  
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        $returnData = array(
            'code' => '00',
            'message' => 'success',
            'data' => $vnp_Url
        );
        if (isset($_POST['redirect'])) {
            header('Location: ' . $vnp_Url);
            die();
        } else {
            echo json_encode($returnData);
        }
    }
    public function thankyoupayment(string $id)
    {
        try {
            if (isset($_GET['vnp_Amount'])) {
                $data_vnpay = [
                    'order_id' => $id,
                    'vnp_Amount' => $_GET['vnp_Amount'],
                    'vnp_BankCode' => $_GET['vnp_BankCode'],
                    'vnp_BankTranNo' => $_GET['vnp_BankTranNo'],
                    'vnp_OrderInfo' => $_GET['vnp_OrderInfo'],
                    'vnp_ResponseCode' => $_GET['vnp_ResponseCode'],
                    'vnp_TmnCode' => $_GET['vnp_TmnCode'],
                    'vnp_TransactionNo' => $_GET['vnp_TransactionNo'],
                    'vnp_TransactionStatus' => $_GET['vnp_TransactionStatus'],
                    'vnp_TxnRef' => $_GET['vnp_TxnRef'],
                    'vnp_SecureHash' => $_GET['vnp_SecureHash']
                ];
                DB::transaction(function () use ($data_vnpay) {
                    $VnpayPayment = VnpayPayment::query()->create($data_vnpay);
                    Order::query()->where('id', $VnpayPayment->order_id)->update(['status_payment' => 'paid']);
                });

                return view('client.thankyoupayment');
            } else {
                return back();
            }
        } catch (\Throwable $th) {
            return back()->with('error', 'Thanh toán không thành công !!!');
        }
    }
}
