<?php

namespace ThrottleStudio\ActivityStream\Listeners;

use Illuminate\Database\Eloquent\Model;
use ThrottleStudio\ActivityStream\Enums\FeedTypes;
use ThrottleStudio\ActivityStream\Events\ActivityCreated;
use ThrottleStudio\ActivityStream\Jobs\AddActivityToAllFollowers;
use ThrottleStudio\ActivityStream\Jobs\AddActivityToCustomFeeds;
use ThrottleStudio\ActivityStream\Jobs\AddActivityToFeed;

class ActivityWasCreated
{
    public function handle(ActivityCreated $event)
    {
        $activity = $event->activity;
        $object = $activity->object;

        if ($object->addToFlatFeed()) {
            dispatch(new AddActivityToFeed($activity, FeedTypes::FLAT));
        }

        if ($object->addToTimeline()) {
            dispatch(new AddActivityToFeed($activity, FeedTypes::TIMELINE));
        }

        if ($notifiable = $object->getNotify()) {
            if ($notifiable instanceof Model) {
                dispatch(new AddActivityToFeed($activity, FeedTypes::NOTIFICATION, $notifiable));
            }
        }

        if (sizeof($feeds = $object->addToCustomFeeds()) > 0) {
            dispatch(new AddActivityToCustomFeeds($activity, $feeds));
        }

        if ($activity->verb != config('activity-stream.follow_verb')) {
            dispatch(new AddActivityToAllFollowers($activity));
        }
    }
}
