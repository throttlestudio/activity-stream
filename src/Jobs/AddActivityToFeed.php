<?php

namespace ThrottleStudio\ActivityStream\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use ThrottleStudio\ActivityStream\Enums\FeedEventTypes;
use ThrottleStudio\ActivityStream\Events\FeedUpdated;
use ThrottleStudio\ActivityStream\Models\Activity;

class AddActivityToFeed implements ShouldQueue
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
     * The feedType instance.
     *
     * @var string
     */
    protected $feedType;

    /**
     * The notifiable instance.
     *
     * @var \Illuminate\Database\Eloquent\Model|null
     */
    protected $notifiable;

    /**
     * Create a new job instance.
     *
     * @param  \ThrottleStudio\ActivityStream\Models\Activity  $activity
     * @param string $feedType
     * @param \Illuminate\Database\Eloquent\Model|null $notifiable
     *
     * @return void
     */
    public function __construct(Activity $activity, string $feedType, $notifiable = null)
    {
        $this->activity = $activity;
        $this->feedType = $feedType;
        $this->notifiable = $notifiable;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $activity = $this->activity;
        $feedType = $this->feedType;
        $object = $activity->object;
        $actor = $this->notifiable ?? $object->getFeedOwner();

        $actor
            ->feeds()
            ->create([
                'type' => $feedType,
                'activity_id' => $activity->id,
                'created_at' => $activity->created_at,
                'updated_at' => $activity->updated_at,
            ]);

        event(new FeedUpdated($actor, $activity, $feedType, FeedEventTypes::CREATED));
    }
}
