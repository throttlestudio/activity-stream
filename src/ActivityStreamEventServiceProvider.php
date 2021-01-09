<?php

namespace ThrottleStudio\ActivityStream;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use ThrottleStudio\ActivityStream\Events\ActivityCreated;
use ThrottleStudio\ActivityStream\Events\ActivityDeleting;
use ThrottleStudio\ActivityStream\Events\FollowCreated;
use ThrottleStudio\ActivityStream\Events\FollowDeleting;
use ThrottleStudio\ActivityStream\Listeners\ActivityWasCreated;
use ThrottleStudio\ActivityStream\Listeners\ActivityIsDeleting;
use ThrottleStudio\ActivityStream\Listeners\FollowIsDeleting;
use ThrottleStudio\ActivityStream\Listeners\FollowWasCreated;

class ActivityStreamEventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ActivityCreated::class => [
            ActivityWasCreated::class,
        ],
        ActivityDeleting::class => [
            ActivityIsDeleting::class,
        ],
        FollowCreated::class => [
            FollowWasCreated::class,
        ],
        FollowDeleting::class => [
            FollowIsDeleting::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
