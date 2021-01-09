<?php

namespace ThrottleStudio\ActivityStream\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use ThrottleStudio\ActivityStream\Enums\FeedEventTypes;
use ThrottleStudio\ActivityStream\Enums\FeedTypes;
use ThrottleStudio\ActivityStream\Events\FeedUpdated;
use ThrottleStudio\ActivityStream\Models\Activity;

class AddActivityToAllFollowers implements ShouldQueue
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
        $actor = $activity->actor;

        if (method_exists($actor, 'followers'))
        {
            $followers = $actor->followers()->get();

            foreach($followers as $follower)
            {
                $follower->follower
                    ->feeds()
                    ->create([
                        'type' => FeedTypes::TIMELINE,
                        'activity_id' => $activity->id,
                        'created_at' => $activity->created_at,
                        'updated_at' => $activity->updated_at,
                    ]);

                event(new FeedUpdated($follower->follower, $activity, FeedTypes::TIMELINE, FeedEventTypes::CREATED));
            }
        }
    }
}
