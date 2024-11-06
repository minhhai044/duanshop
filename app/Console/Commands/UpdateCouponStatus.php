<?php

namespace App\Console\Commands;

use App\Models\Coupon;
use Illuminate\Console\Command;

class UpdateCouponStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-coupon-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cập nhật trạng thái của coupon khi hết hạn';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Coupon::where('end_date', '<=', now())
            ->where('coupon_status', '!=', 0) 
            ->update(['coupon_status' => 0]);
    }
}
