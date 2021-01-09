<?php

namespace ThrottleStudio\ActivityStream\Traits;

use Illuminate\Database\Eloquent\Model;
use ThrottleStudio\ActivityStream\Models\Follow;

trait CanFollow
{
    /**
     * Entity follows another entity
     *
     * @var \Illumiate\Database\Eloquent\Model
     *
     * @return \ThrottleStudio\ActivityStream\Models\Follow
     */
    public function follow(Model $model)
    {
        return $this->follows()->create([
            'followable_id' => $model->getKey(),
            'followable_type' => $model->getMorphClass()
        ]);
    }

    /**
     * Entity unfollows another entity
     *
     * @var \Illumiate\Database\Eloquent\Model
     *
     * @return void
     */
    public function unfollow(Model $model)
    {
        $follow = $this->follows()
            ->where('followable_id', $model->getKey())
            ->where('followable_type', $model->getMorphClass())
            ->first();

        if ($follow)
        {
            $follow->delete();
        }
    }

    /**
     * Entity unfollows another entity
     *
     * @var \Illumiate\Database\Eloquent\Model
     *
     * @return void|\ThrottleStudio\ActivityStream\Models\Follow
     */
    public function toggleFollow(Model $model)
    {
        if ($this->isFollowing($model)) {
            $this->unfollow($model);
            return;
        }

        return $this->follow($model);
    }

    /**
     * Check if entity is following another entity
     *
     * @var \Illumiate\Database\Eloquent\Model
     *
     * @return bool
     */
    public function isFollowing(Model $model)
    {
        return !!$this->follows()
            ->where('followable_id', $model->getKey())
            ->where('followable_type', $model->getMorphClass())
            ->count();
    }

    /**
     * Get count of follows
     */
    public function getFollowingCountAttribute()
    {
        return $this->follows()->count();
    }

    /**
     * Get all entities you are following
     */
    public function follows()
    {
        return $this->morphMany(Follow::class, 'follower');
    }
}
