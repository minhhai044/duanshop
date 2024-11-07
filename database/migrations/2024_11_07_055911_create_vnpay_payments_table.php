<?php

use App\Models\Order;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vnpay_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Order::class)->constrained();
            $table->string('vnp_Amount', 50);
            $table->string('vnp_BankCode', 50);
            $table->string('vnp_BankTranNo', 50);
            $table->string('vnp_OrderInfo', 50);
            $table->string('vnp_ResponseCode', 50);
            $table->string('vnp_TmnCode', 50);
            $table->string('vnp_TransactionNo', 50);
            $table->string('vnp_TransactionStatus', 50);
            $table->string('vnp_TxnRef', 50);
            $table->text('vnp_SecureHash');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vnpay_payments');
    }
};
