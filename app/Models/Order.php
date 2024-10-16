<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    const STATUS_ORDER = [
        'pending' => 'Chờ xác nhận',
        'confirmed' => 'Đã xác nhận',
        'preparing_goods' => 'Đang chuẩn bị hàng',
        'shipping' => 'Đang vận chuyển',
        'delivered' => 'Đã giao hàng',
        'canceled' => 'Đơn hàng đã bị hủy',
    ];

    const METHOD_PAYMENT = [
        'cash_delivery' => 'Thanh toán khi nhận hàng',
        'vnpay_payment' => 'Thanh toán VNPay'
    ];

    const STATUS_PAYMENT = [
        'unpaid' => 'Chưa thanh toán',
        'paid' => 'Đã thanh toán',
    ];

    const STATUS_ORDER_PENDING = 'pending';
    const STATUS_ORDER_CONFIRMED = 'confirmed';
    const STATUS_ORDER_PREPARING_GOODS = 'preparing_goods';
    const STATUS_ORDER_SHIPPING = 'shipping';
    const STATUS_ORDER_DELIVERED = 'delivered';
    const STATUS_ORDER_CANCELED = 'canceled';


    const METHOD_PAYMENT_DELIVERY = 'cash_delivery';
    const METHOD_PAYMENT_VNPAY = 'vnpay_payment';



    const STATUS_PAYMENT_UNPAID = 'unpaid';
    const STATUS_PAYMENT_PAID = 'paid';

    protected $fillable = [
        'user_id',
        'order_user_name',
        'order_user_email',
        'order_user_phone',
        'order_user_address',
        'order_user_note',
        'status_order',
        'method_payment',
        'status_payment',
        'order_total_price',
    ];
    
}
