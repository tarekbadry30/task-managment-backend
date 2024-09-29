<?php

namespace App\Providers;

use App\Events\Task\TaskCreatedEvent;
use App\Events\Task\TaskDeletedEvent;
use App\Events\Task\TaskUpdatedEvent;
use App\Listeners\Task\TaskCreatedListener;
use App\Listeners\Task\TaskDeletedListener;
use App\Listeners\Task\TaskUpdatedListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        TaskCreatedEvent::class=>[
            TaskCreatedListener::class
        ],
        TaskUpdatedEvent::class=>[
            TaskUpdatedListener::class
        ],
        TaskDeletedEvent::class=>[
            TaskDeletedListener::class
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
