<?php

namespace App\Listeners;

use App\Events\NewWorkOrderEvent;
use App\Mail\RegistrationCompletedMail;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use \App\Mail\NewWorkOrderEmail;

class NewWorkOrderListener
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
    public function handle(NewWorkOrderEvent $event): void
    {
        Notification::create([
            'region_id' => $event->workOrder->user->region_id,
            'message' => 'New work order: '.$event->workOrder->name,
            'url' => route('work.inspect', $event->workOrder->id),
            'users_notification' => false,
        ]);

        $admins = User::where([
            'region_id' => $event->workOrder->user->region_id,
            'role' => 'admin'
        ])->get();

        foreach ($admins as $admin){
            Mail::to($admin->email)->send(new NewWorkOrderEmail($event->workOrder));
        }
    }
}
