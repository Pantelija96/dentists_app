<?php

namespace App\Listeners;

use App\Mail\RegistrationCompletedMail;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SecondStepSignUpFinished
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
    public function handle(\App\Events\SecondStepSignUpFinished $event): void
    {
        Notification::create([
            'region_id' => $event->user->region_id,
            'message' => 'New user registration: '.$event->user->first_name.' '.$event->user->last_name,
            'url' => route('admin.dashboard'),
            'users_notification' => false,
        ]);

        $admins = User::where([
            'region_id' => $event->user->region_id,
            'role' => 'admin'
        ])->get();

        foreach ($admins as $admin){
            Mail::to($admin->email)->send(new RegistrationCompletedMail($event->user));
        }

        Mail::to($event->user->email)->send(new RegistrationCompletedMail($event->user));
    }
}
