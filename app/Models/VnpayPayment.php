<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VnpayPayment extends Model
{
    use HasFactory;
    protected $fillable = [
        'vnp_Amount',
        'vnp_BankCode',
        'vnp_BankTranNo',
        'vnp_OrderInfo',
        'vnp_ResponseCode',
        'vnp_TmnCode',
        'vnp_TransactionNo',
        'vnp_TransactionStatus',
        'vnp_TxnRef',
        'vnp_SecureHash',
        'order_id'
    ];
}
