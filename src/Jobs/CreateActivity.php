<?php

namespace ThrottleStudio\ActivityStream\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use ThrottleStudio\ActivityStream\Models\Activity;

class CreateActivity
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * The activity instance.
     *
     * @var \Illuminate\Database\Eloquent\Model;
     */
    protected $model;

    /**
     * Create a new job instance.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     *
     * @return void
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $activity = [];
        // Set the verb
        $activity['verb'] = $this->model->getVerb();

        // Set the actor
        $actor = $this->model->getActor();
        $activity['actor_id'] = $actor->id;
        $activity['actor_type'] = $actor->getMorphClass();

        // Set the object
        $object = $this->model;
        $activity['object_id'] = $object->id;
        $activity['object_type'] = $object->getMorphClass();

        // Set the target
        if ($target = $this->model->getTarget()) {
            $activity['target_id'] = $target->id;
            $activity['target_type'] = $target->getMorphClass();
        }

        $activity['created_at'] = $this->model->created_at;
        $activity['updated_at'] = $this->model->updated_at;

        // Save activity
        Activity::firstOrCreate($activity);
    }
}
