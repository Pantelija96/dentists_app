<?php

namespace App\Listeners;

use App\Events\NewCommentEvent;
use App\Mail\NewCommentMail;
use App\Mail\NewWorkOrderEmail as NewWorkOrderMail;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class NewCommentListener
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
    public function handle(NewCommentEvent $event): void
    {
        if(auth()->user()->role == 'user'){
            //user is logged, new notificvation+email is for admin
            Notification::create([
                'region_id' => $event->comment->workOrder->user->region_id,
                'message' => 'New work order comment: '.$event->comment->workOrder->name,
                'url' => route('work.inspect', $event->comment->workOrder->id),
                'users_notification' => false,
            ]);

            $admins = User::where([
                'region_id' => $event->comment->workOrder->user->region_id,
                'role' => 'admin'
            ])->get();

            foreach ($admins as $admin){
                Mail::to($admin->email)->send(new NewCommentMail($event->comment));
            }
        }

        else{
            Notification::create([
                'user_id' => $event->comment->workOrder->user->id,
                'region_id' => null,
                'message' => 'New work order comment: '.$event->comment->workOrder->name,
                'url' => route('work.inspect', $event->comment->workOrder->id),
                'users_notification' => true,
            ]);

            Mail::to($event->comment->workOrder->user->email)->send(new NewCommentMail($event->comment));
        }

    }
}
