<?php

namespace ThrottleStudio\ActivityStream\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use ThrottleStudio\ActivityStream\Enums\FeedEventTypes;
use ThrottleStudio\ActivityStream\Enums\FeedTypes;
use ThrottleStudio\ActivityStream\Events\FeedUpdated;
use ThrottleStudio\ActivityStream\Models\Activity;
use ThrottleStudio\ActivityStream\Models\Feed;

class RemoveActivityFromAllFollowers implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * The activity instance.
     *
     * @var \ThrottleStudio\ActivityStream\Models\Activity
     */
    protected $activity;

    /**
     * Create a new job instance.
     *
     * @param  \ThrottleStudio\ActivityStream\Models\Activity  $activity
     *
     * @return void
     */
    public function __construct(Activity $activity)
    {
        $this->activity = $activity;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $activity = $this->activity;
        $object = $activity->object;
        $actor = $object->getFeedOwner();

        if (method_exists($actor, 'followers')) {
            $followers = $actor->followers()->get();

            Feed::where('type', FeedTypes::TIMELINE)
                ->where('activity_id', $activity->id)
                ->delete();

            foreach ($followers as $follower) {
                event(new FeedUpdated($follower, $activity, FeedTypes::TIMELINE, FeedEventTypes::DELETED));
            }
        }
    }
}
