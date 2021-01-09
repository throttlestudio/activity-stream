<?php

namespace ThrottleStudio\ActivityStream\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use ThrottleStudio\ActivityStream\Events\ActivityCreated;
use ThrottleStudio\ActivityStream\Events\ActivityDeleted;
use ThrottleStudio\ActivityStream\Events\ActivityDeleting;

class Activity extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The event map for the model.
     *
     * Allows for object-based events for native Eloquent events.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => ActivityCreated::class,
        'deleting' => ActivityDeleting::class,
        'deleted' => ActivityDeleted::class,
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'extra' => 'array',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'actor', 'object', 'target',
    ];

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->table = \config('activity-stream.tables.activities');

        parent::__construct($attributes);
    }

    /**
     * Model belongs to an actor.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function actor(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Model belongs to an object.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function object(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Model belongs to target.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo|null
     */
    public function target(): MorphTo
    {
        return $this->morphTo();
    }
}
