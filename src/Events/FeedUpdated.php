<?php

namespace ThrottleStudio\ActivityStream\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use ThrottleStudio\ActivityStream\Models\Activity;

class FeedUpdated
{
    use Dispatchable;
    use SerializesModels;

    /**
     * The model instance.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $model;

    /**
     * The activity instance.
     *
     * @var \ThrottleStudio\ActivityStream\Models\Activity
     */
    public $activity;

    /**
     * The feed type instance.
     *
     * @var string flat|timeline|notification
     */
    public $feedType;

    /**
     * The event type instance.
     *
     * @var string created|deleted
     */
    public $eventType;

    /**
     * Create a new event instance.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function __construct(Model $model, Activity $activity, string $feedType, string $eventType)
    {
        $this->model = $model;
        $this->activity = $activity;
        $this->feedType = $feedType;
        $this->eventType = $eventType;
    }
}
