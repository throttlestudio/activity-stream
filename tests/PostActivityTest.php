<?php

namespace ThrottleStudio\ActivityStream\Tests;

use ThrottleStudio\ActivityStream\Tests\TestCase;
use ThrottleStudio\ActivityStream\Enums\FeedTypes;

class PostActivityTest extends TestCase
{
    /** @test */
    public function user_creates_a_post_then_deletes_it()
    {
        $user = $this->createUser();
        $post = $this->createPost($user);

        $this->assertCount(1, $user->getFlatFeed(), 'flat feed has one item');

        $this->assertDatabaseHas(
            config('activity-stream.tables.activities'),
            $this->findActivityEntry($post->getVerb(), $user, $post)
        );
        $this->assertDatabaseHas(
            config('activity-stream.tables.feeds'),
            $this->findFeedEntry('1', FeedTypes::FLAT, $user, $post)
        );

        // Delete Post
        $post->delete();

        $this->assertCount(0, $user->getFlatFeed(), 'has no feed item');

        $this->assertDatabaseMissing(
            config('activity-stream.tables.activities'),
            $this->findActivityEntry($post->getVerb(), $user, $post)
        );
        $this->assertDatabaseMissing(
            config('activity-stream.tables.feeds'),
            $this->findFeedEntry('1', FeedTypes::FLAT, $user, $post)
        );
    }
}
