<?php

namespace App\Events;

use App\Models\Coupon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CouponEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $coupon;
    public function __construct(Coupon $coupon)
    {
        $this->coupon = $coupon;
    }


    public function broadcastOn()
    {
        return new Channel('broadcast_coupon');
    }
    public function broadcastWith()
    {
        return [
            'code' => $this->coupon->coupon_code,
            'start' => $this->coupon->start_date,
            'end' => $this->coupon->end_date,
            'description' => $this->coupon->coupon_description,
        ];
    }
}
