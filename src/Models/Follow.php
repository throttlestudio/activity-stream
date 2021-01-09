<?php

namespace ThrottleStudio\ActivityStream\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use ThrottleStudio\ActivityStream\Contracts\StreamActivity;
use ThrottleStudio\ActivityStream\Events\FollowCreated;
use ThrottleStudio\ActivityStream\Events\FollowDeleted;
use ThrottleStudio\ActivityStream\Events\FollowDeleting;
use ThrottleStudio\ActivityStream\Traits\LogsStreamActivity;

class Follow extends Model implements StreamActivity
{
    use LogsStreamActivity;

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
        'created' => FollowCreated::class,
        'deleting' => FollowDeleting::class,
        'deleted' => FollowDeleted::class,
    ];

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->table = \config('activity-stream.tables.follow');

        parent::__construct($attributes);
    }

    public function addToFlatFeed()
    {
        return false;
    }

    public function addToTimeline()
    {
        return false;
    }

    public function getVerb(): string
    {
        return config('activity-stream.follow_verb');
    }

    public function getActor(): Model
    {
        return $this->follower;
    }

    public function getTarget()
    {
        return $this->followable;
    }

    public function getFeedOwner(): Model
    {
        return $this->followable;
    }

    public function getNotify()
    {
        return $this->followable;
    }

    /**
     * Model belongs to a follower
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function follower(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Model belongs to a Followable model
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function followable(): MorphTo
    {
        return $this->morphTo();
    }
}
