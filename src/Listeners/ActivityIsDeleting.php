<?php

namespace ThrottleStudio\ActivityStream\Listeners;

use Illuminate\Database\Eloquent\Model;
use ThrottleStudio\ActivityStream\Enums\FeedTypes;
use ThrottleStudio\ActivityStream\Events\ActivityDeleting;
use ThrottleStudio\ActivityStream\Jobs\RemoveActivityFromAllFollowers;
use ThrottleStudio\ActivityStream\Jobs\RemoveActivityFromCustomFeeds;
use ThrottleStudio\ActivityStream\Jobs\RemoveActivityFromFeed;

class ActivityIsDeleting
{
    public function handle(ActivityDeleting $event)
    {
        $activity = $event->activity;
        $object = $activity->object;

        if ($object->addToFlatFeed()) {
            dispatch(new RemoveActivityFromFeed($activity, FeedTypes::FLAT));
        }

        if ($object->addToTimeline()) {
            dispatch(new RemoveActivityFromFeed($activity, FeedTypes::TIMELINE));
        }

        if ($notifiable = $object->getNotify()) {
            if ($notifiable instanceof Model) {
                dispatch(new RemoveActivityFromFeed($activity, FeedTypes::NOTIFICATION, $notifiable));
            }
        }

        if (sizeof($feeds = $object->addToCustomFeeds()) > 0) {
            dispatch(new RemoveActivityFromCustomFeeds($activity, $feeds));
        }

        if ($activity->verb != config('activity-stream.follow_verb')) {
            dispatch(new RemoveActivityFromAllFollowers($activity));
        }
    }
}
