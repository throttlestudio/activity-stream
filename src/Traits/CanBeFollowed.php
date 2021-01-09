<?php

namespace ThrottleStudio\ActivityStream\Traits;

use Illuminate\Database\Eloquent\Model;
use ThrottleStudio\ActivityStream\Models\Follow;

trait CanBeFollowed
{
    /**
     * Check if entity is being followed by another entity.
     *
     * @var \Illumiate\Database\Eloquent\Model
     *
     * @return bool
     */
    public function isFollowedBy(Model $model)
    {
        return (bool) $this->followers()
            ->where('follower_id', $model->getKey())
            ->where('follower_type', $model->getMorphClass())
            ->count();
    }

    /**
     * Get all entities following you.
     */
    public function followers()
    {
        return $this->morphMany(Follow::class, 'followable');
    }

    /**
     * Get count of followers.
     */
    public function getFollowersCountAttribute()
    {
        return $this->followers()->count();
    }
}
