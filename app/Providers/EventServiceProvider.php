<?php

namespace App\Providers;

use App\Events\AppNotificationEvent;
use App\Events\UserApproved;
use App\Events\WorkOrderCreated;
use App\Events\WorkOrderStatusChange;
use App\Listeners\HandleAppNotificationEvent;
use App\Listeners\NotifyRegionAdminOfWorkOrder;
use App\Listeners\NotifyUserAboutWorkOrderStatusChange;
use App\Listeners\SendUserEmail;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        AppNotificationEvent::class => [
            HandleAppNotificationEvent::class,
        ],
        UserApproved::class => [
            SendUserEmail::class,
        ],
        WorkOrderCreated::class => [
            NotifyRegionAdminOfWorkOrder::class,
        ],
        WorkOrderStatusChange::class => [
            NotifyUserAboutWorkOrderStatusChange::class,
        ],
    ];
}
