<?php

namespace App\Listeners;

use App\Events\AppNotificationEvent;
use App\Mail\RegistrationCompletedMail;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class HandleAppNotificationEvent
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
    public function handle(AppNotificationEvent $event)
    {
        // USER NOTIFICATION (only if message exists)
        if ($event->userMessage != null) {
            Notification::create([
                'user_id' => $event->user->id,
                'message' => $event->userMessage,
                'users_notification' => true,
            ]);
        }

        // ADMIN NOTIFICATION (region-based)
        if ($event->adminMessage != null) {
            Notification::create([
                'region_id' => $event->user->region_id,
                'message' => $event->adminMessage,
                'users_notification' => false,
            ]);
        }

        Mail::to($event->user->email)->send(new RegistrationCompletedMail($event->user));
    }
}
