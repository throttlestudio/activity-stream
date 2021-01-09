<?php

namespace ThrottleStudio\ActivityStream\Listeners;

use ThrottleStudio\ActivityStream\Events\FollowDeleting;
use ThrottleStudio\ActivityStream\Jobs\RemoveActivityFromFollowersTimeline;

class FollowIsDeleting
{
    public function handle(FollowDeleting $event)
    {
        dispatch(new RemoveActivityFromFollowersTimeline($event->follow));
    }
}
