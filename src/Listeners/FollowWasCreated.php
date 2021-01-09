<?php

namespace ThrottleStudio\ActivityStream\Listeners;

use ThrottleStudio\ActivityStream\Events\FollowCreated;
use ThrottleStudio\ActivityStream\Jobs\AddActivityToFollowersTimeline;

class FollowWasCreated
{
    public function handle(FollowCreated $event)
    {
        dispatch(new AddActivityToFollowersTimeline($event->follow));
    }
}
