<?php

namespace App\Listeners;

use App\Events\OtpGenerated;
use App\Mail\OtpMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendOtpNotification implements ShouldQueue
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
    public function handle(OtpGenerated $event): void
    {
        $otp = $event->otp;
        $email = $event->email;
        Mail::to($email)->send(new OtpMail($otp, $email));
    }
}
