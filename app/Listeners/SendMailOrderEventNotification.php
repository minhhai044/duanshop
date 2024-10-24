<?php

namespace App\Listeners;

use App\Events\SendMailOrderEvent;
use App\Mail\OrdersMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendMailOrderEventNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SendMailOrderEvent $event): void
    {
        $email = $event->email;
        $name = $event->name;
        $total = $event->total;
        $status = $event->status;
        Mail::to($email)->send(new OrdersMail( $email,$name,$total,$status));
    }
}
