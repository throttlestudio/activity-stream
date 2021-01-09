<?php

namespace ThrottleStudio\ActivityStream\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;
use ThrottleStudio\ActivityStream\Enums\FeedEventTypes;
use ThrottleStudio\ActivityStream\Enums\FeedTypes;
use ThrottleStudio\ActivityStream\Events\FeedUpdated;
use ThrottleStudio\ActivityStream\Models\Activity;
use ThrottleStudio\ActivityStream\Models\CustomFeed;
use ThrottleStudio\ActivityStream\Models\Feed;

class RemoveActivityFromCustomFeeds implements ShouldQueue
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
     * The feeds instance.
     *
     * @var array
     */
    protected $feeds;

    /**
     * Create a new job instance.
     *
     * @param  \ThrottleStudio\ActivityStream\Models\Activity  $activity
     * @param array $feeds
     *
     * @return void
     */
    public function __construct(Activity $activity, array $feeds)
    {
        $this->activity = $activity;
        $this->feeds = $feeds;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $activity = $this->activity;
        $feeds = $this->feeds;

        // Loop through each custom feed
        foreach ($feeds as $feedClass) {
            if (! class_exists($feedClass)) {
                Log::info("Class {$feedClass} doesn't exist");
            }

            if (class_exists($feedClass)) {
                // Create class instance
                $feed = new $feedClass();

                // Check instance
                if (! $feed instanceof CustomFeed) {
                    Log::info("Class {$feedClass} not instance of CustomFeed");
                }

                if ($feed instanceof CustomFeed) {
                    Feed::where([
                        'owner_id' => $feed->id,
                        'owner_type' => $feed->getMorphClass(),
                        'type' => FeedTypes::NOTIFICATION,
                        'activity_id' => $activity->id,
                    ])->delete();

                    event(new FeedUpdated($feed, $activity, FeedTypes::NOTIFICATION, FeedEventTypes::CREATED));
                }
            }
        }
    }
}
