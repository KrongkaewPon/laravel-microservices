<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Auth\Events\Registered;
use App\Listeners\ProductUpdatedListener;
use App\Listeners\NotifyAmbassadorListener;
use App\Listeners\NotifyAdminListener;
use App\Events\ProductUpdatedEvent;
use App\Events\OrderCompletedEvent;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        ProductUpdatedEvent::class => [
            ProductUpdatedListener::class
        ],
        // OrderCompletedEvent::class => [
        //     NotifyAdminListener::class,
        //     NotifyAmbassadorListener::class
        // ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
