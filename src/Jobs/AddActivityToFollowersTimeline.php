<?php

namespace ThrottleStudio\ActivityStream\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use ThrottleStudio\ActivityStream\Enums\FeedEventTypes;
use ThrottleStudio\ActivityStream\Enums\FeedTypes;
use ThrottleStudio\ActivityStream\Events\TimelineUpdated;
use ThrottleStudio\ActivityStream\Models\Follow;

class AddActivityToFollowersTimeline implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * The follow instance.
     *
     * @var \ThrottleStudio\ActivityStream\Models\Follow
     */
    protected $follow;

    /**
     * Create a new job instance.
     *
     * @param  \ThrottleStudio\ActivityStream\Models\Follow  $follow
     *
     * @return void
     */
    public function __construct(Follow $follow)
    {
        $this->follow = $follow;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $follow = $this->follow;
        $follower = $follow->follower;
        $followable = $follow->followable;

        // Get followable feed item count based on config
        $feed = $followable->getFlatFeed(config('activity-stream.follow_feed_count'));

        foreach ($feed as $item) {
            $follower
                ->feeds()
                ->create([
                    'type' => FeedTypes::TIMELINE,
                    'activity_id' => $item->activity_id,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                ]);
        }

        // Call event that timeline was updated
        event(new TimelineUpdated($follower, FeedEventTypes::CREATED));
    }
}
