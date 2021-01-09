<?php

namespace ThrottleStudio\ActivityStream\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TimelineUpdated
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
    public function __construct(Model $model, string $eventType)
    {
        $this->model = $model;
        $this->eventType = $eventType;
    }
}
