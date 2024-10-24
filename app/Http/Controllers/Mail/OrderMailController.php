<?php

namespace App\Http\Controllers\Mail;

use App\Events\SendMailOrderEvent;
use App\Http\Controllers\Controller;
use App\Mail\OrdersMail;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OrderMailController extends Controller
{
    public function sendMailOrders($id)
    {
        if (Auth::check()) {
            $order = Order::query()->findOrFail($id);
            $email = $order->order_user_email;
            $name = $order->order_user_name;
            $total = $order->order_total_price;
            $status = $order->status_order;

            SendMailOrderEvent::dispatch($email, $name, $total, $status);
        }
        return view('client.thankyou');
    }
}
