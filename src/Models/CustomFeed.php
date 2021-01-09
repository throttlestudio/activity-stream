<?php

namespace ThrottleStudio\ActivityStream\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class CustomFeed extends Model
{
    public $incrementing = false;

    /**
     * Relationship collection of all items in flat feed.
     *
     * @return \ThrottleStudio\ActivityStream\Models\Feed
     */
    public function feeds(): MorphMany
    {
        return $this->morphMany(Feed::class, 'owner');
    }

    /**
     * Get the timeline for this custom feed.
     *
     * @var int|null
     *
     * @return \Illuminate\Database\Eloquent\Collection;
     */
    public function getFeed($pagination = null)
    {
        $paginate = $pagination ?? config('activity-stream.feed_pagination');

        return Feed::where('owner_id', $this->id)
            ->where('owner_type', $this->getMorphClass())
            ->paginate($paginate);
    }
}
