<?php

namespace App\Listeners;

use App\Events\WorkOrderStatusChange;
use App\Mail\AdminChangedWorkOrderStatus;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class NotifyUserAboutWorkOrderStatusChange
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
    public function handle(WorkOrderStatusChange $event): void
    {
        $workOrder = $event->work_order;

        $user = User::where('id', $workOrder->user->id);
        $user->increment('number_of_notifications');

        Notification::create([
            'region_id' => null,
            'user_id' => $workOrder->user->id,
            'message' => "Status change for work order: ".$workOrder->name,
            'users_notification' => true,
        ]);

        Mail::to($workOrder->user->email)->send(new AdminChangedWorkOrderStatus($workOrder));
    }
}
