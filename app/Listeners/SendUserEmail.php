<?php

namespace App\Listeners;

use App\Mail\UserApproved;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendUserEmail
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
    public function handle(UserApproved $event)
    {
        Mail::to($event->user->email)
            ->send(new UserApproved($event->user));
    }
}
