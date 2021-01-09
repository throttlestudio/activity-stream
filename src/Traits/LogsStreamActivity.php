<?php

namespace ThrottleStudio\ActivityStream\Traits;

use Illuminate\Database\Eloquent\Relations\MorphOne;
use ThrottleStudio\ActivityStream\Models\Activity;

trait LogsStreamActivity
{
    /**
     * Listen for various events on this model.
     *
     * @return void
     */
    public static function bootLogsStreamActivity()
    {
        static::created(function ($model) {
            $model->createActivity();
        });
        static::deleting(function ($model) {
            $model->deleteActivity();
        });
    }

    /**
     * Should this activity be added to owners flat feed.
     *
     * @return bool
     */
    public function addToFlatFeed()
    {
        return true;
    }

    /**
     * Should this activity be added to owners timeline.
     *
     * @return bool
     */
    public function addToTimeline()
    {
        return false;
    }

    /**
     * Should this activity be added to any custom feeds.
     *
     * @return array
     */
    public function addToCustomFeeds()
    {
        return [];
    }

    /**
     * Get the target reference for this model.
     *
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|null
     */
    public function getTarget()
    {
        return null;
    }

    /**
     * Get the notification reference for this model.
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function getNotify()
    {
        return null;
    }

    /**
     * This model belongs to one activity.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function streamActivity(): MorphOne
    {
        return $this->morphOne(Activity::class, 'object');
    }

    /**
     * Create a new Stream Activity.
     */
    protected function createActivity()
    {
        $activity = new Activity();

        // Set the verb
        $activity->verb = $this->getVerb();

        // Set the actor
        $actor = $this->getActor();
        $activity->actor_id = $actor->id;
        $activity->actor_type = $actor->getMorphClass();

        // Set the object
        $object = $this;
        $activity->object_id = $object->id;
        $activity->object_type = $object->getMorphClass();

        // Set the target
        if ($target = $this->getTarget()) {
            $activity->target_id = $target->id;
            $activity->target_type = $target->getMorphClass();
        }

        // Set extra data

        // Save activity
        $activity->save();

        return $activity;
    }

    /**
     * Delete the created Stream Activity.
     */
    protected function deleteActivity()
    {
        $this->streamActivity->delete();
    }
}
