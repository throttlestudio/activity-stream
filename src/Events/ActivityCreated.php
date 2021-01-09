<?php

namespace ThrottleStudio\ActivityStream\Events;

use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use ThrottleStudio\ActivityStream\Models\Activity;

class ActivityCreated
{
    use Dispatchable;
    use SerializesModels;

    /**
     * The activity instance.
     *
     * @var \ThrottleStudio\ActivityStream\Models\Activity
     */
    public $activity;

    /**
     * Create a new event instance.
     *
     * @param  \ThrottleStudio\ActivityStream\Models\Activity  $activity
     * @return void
     */
    public function __construct(Activity $activity)
    {
        $this->activity = $activity;
    }
}
