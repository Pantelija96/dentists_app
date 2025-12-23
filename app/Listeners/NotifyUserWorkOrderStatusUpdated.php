<?php

namespace App\Listeners;

use App\Events\WorkOrderStatusUpdatedEvent;
use App\Mail\AdminChangedWorkOrderStatus;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class NotifyUserWorkOrderStatusUpdated
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
    public function handle(WorkOrderStatusUpdatedEvent $event): void
    {
        $workOrder = $event->workOrder;

        Notification::create([
            'region_id' => null,
            'user_id' => $workOrder->user->id,
            'message' => "Status change for work order: ".$workOrder->name,
            'url' => route('work.inspect', $workOrder->id),
            'users_notification' => true,
        ]);

        \Log::info('Listener NotifyUserAboutWorkOrderStatusChange fired', [
            'work_order_id' => $event->workOrder->id,
        ]);

        Mail::to($workOrder->user->email)->send(new AdminChangedWorkOrderStatus($workOrder));
    }
}
