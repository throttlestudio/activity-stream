<?php

namespace ThrottleStudio\ActivityStream\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use ThrottleStudio\ActivityStream\Enums\FeedEventTypes;
use ThrottleStudio\ActivityStream\Enums\FeedTypes;
use ThrottleStudio\ActivityStream\Events\TimelineUpdated;
use ThrottleStudio\ActivityStream\Models\Follow;

class RemoveActivityFromFollowersTimeline implements ShouldQueue
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

        // Get all feed item activity
        $ids = $followable->feeds()
            ->where('type', FeedTypes::FLAT)
            ->get()
            ->pluck('activity_id');

        // Remove id's from follower timeline
        $follower->feeds()
            ->where('type', FeedTypes::TIMELINE)
            ->whereIn('activity_id', $ids)
            ->delete();

        // Call event that timeline was updated
        event(new TimelineUpdated($follower, FeedEventTypes::DELETED));
    }
}
