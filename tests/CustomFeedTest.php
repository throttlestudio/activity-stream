<?php

namespace ThrottleStudio\ActivityStream\Tests;

use ThrottleStudio\ActivityStream\Models\Feed;
use ThrottleStudio\ActivityStream\Tests\TestCase;
use ThrottleStudio\ActivityStream\Tests\Helpers\Feeds\TrendingFeed;

class CustomFeedTest extends TestCase
{
    /** @test */
    public function user_tweet_added_to_custom_feed_and_removed_when_deleted()
    {
        $user = $this->createUser();
        $tweet = $this->createTweet($user);

        $trending = new TrendingFeed();

        $this->assertCount(1, $user->getFlatFeed(), 'user flat feed has 1 entry');
        $this->assertCount(2, Feed::get(), 'Feed has 2 items');
        $this->assertCount(1, $trending->getFeed(), 'custom feed as 1 entry');

        $tweet->delete();

        $this->assertCount(0, $user->getFlatFeed(), 'user flat feed has 0 entry');
        $this->assertCount(0, Feed::get(), 'Feed has 0 items');
        $this->assertCount(0, $trending->getFeed(), 'custom feed as 0 entry');
    }
}
