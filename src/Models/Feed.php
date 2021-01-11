<?php

namespace ThrottleStudio\ActivityStream\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Feed extends Model
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
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'activity',
    ];

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->table = \config('activity-stream.tables.feeds');

        parent::__construct($attributes);
    }

    /**
     * Get the isOwner reference.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getIsOwnerAttribute()
    {
        return auth()->guest() ? false : $this->activity->actor->id == auth()->id();
    }

    /**
     * Get the verb reference.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getVerbAttribute()
    {
        return $this->activity->verb;
    }

    /**
     * Get the actor reference.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getActorAttribute()
    {
        return $this->activity->actor;
    }

    /**
     * Get the object reference.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getObjectAttribute()
    {
        return $this->activity->object;
    }

    /**
     * Get the target reference.
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function getTargetAttribute()
    {
        return $this->activity->target;
    }

    /**
     * Feed has many activities.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class, 'activity_id');
    }

    /**
     * Feed model belongs to an activity.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class, 'activity_id');
    }
}
