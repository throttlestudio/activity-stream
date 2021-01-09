<?php

namespace ThrottleStudio\ActivityStream\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use ThrottleStudio\ActivityStream\Enums\FeedTypes;
use ThrottleStudio\ActivityStream\Models\Feed;

trait HasFeeds
{
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
     * Returns data scoped to flat feed only.
     */
    public function scopeFlatFeed($query)
    {
        return $query->where('type', FeedTypes::FLAT);
    }

    /**
     * Return a paginated flat feed.
     *
     * @var int|null
     */
    public function getFlatFeed(int $pagination = null)
    {
        return $this->getFeed(FeedTypes::FLAT, $pagination);
    }

    /**
     * Returns data scoped to flat feed only.
     */
    public function scopeTimeline($query)
    {
        return $query->where('type', FeedTypes::TIMELINE);
    }

    /**
     * Return a paginated timeline.
     *
     * @var int|null
     */
    public function getTimeline(int $pagination = null)
    {
        return $this->getFeed(FeedTypes::TIMELINE, $pagination);
    }

    /**
     * Returns data scoped to flat feed only.
     */
    public function scopeNotifications($query)
    {
        return $query->where('type', FeedTypes::NOTIFICATION);
    }

    /**
     * Return a paginated notifications.
     *
     * @var int|null
     */
    public function getNotifications(int $pagination = null)
    {
        return $this->getFeed(FeedTypes::NOTIFICATION, $pagination);
    }

    /**
     * Returns a paginated flat feed in decending order.
     *
     * @var oneof \ThrottleStudio\ActivityStream\Enums\FeedTypes
     * @var int|null
     */
    protected function getFeed(string $feedType, $pagination = null)
    {
        $paginate = $pagination ?? config('activity-stream.feed_pagination');

        return $this->feeds()
            ->where('type', $feedType)
            ->orderBy('created_at', 'DESC')
            ->paginate($paginate);
    }
}
