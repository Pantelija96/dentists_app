<?php

namespace App\Listeners;

use App\Events\WorkOrderCreated;
use App\Mail\UserCreatedNewWorkOrder;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class NotifyRegionAdminOfWorkOrder
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
    public function handle(WorkOrderCreated $event)
    {
        $workOrder = $event->work_order;

        $region_id = $workOrder->user->region_id;

        $admin = User::where([
            'region_id' => $region_id,
            'role' => 1
        ])->first();

        $admin->increment('number_of_notifications');

        Notification::create([
            'region_id' => $region_id,
            'message' => "User ".$workOrder->user->first_name." ".$workOrder->user->last_name." created new work order!",
            'users_notification' => false,
        ]);

        Mail::to($admin->email)->send(new UserCreatedNewWorkOrder($admin, $workOrder));
    }
}
