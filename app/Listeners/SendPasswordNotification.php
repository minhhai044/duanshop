<?php

namespace App\Listeners;

use App\Events\PasswordGenerated;
use App\Mail\OtpMail;
use App\Mail\PasswordMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendPasswordNotification implements ShouldQueue
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
    public function handle(PasswordGenerated $event): void
    {
        $password = $event->passsword;
        $email = $event->email;
        Mail::to($email)->send(new PasswordMail($password, $email));
    }
}
