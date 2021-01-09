<?php

namespace ThrottleStudio\ActivityStream\Tests;

use ThrottleStudio\ActivityStream\Tests\TestCase;
use ThrottleStudio\ActivityStream\Enums\FeedTypes;

class CommentActivityTest extends TestCase
{
    /** @test */
    public function user_creates_a_comment_then_deletes_it()
    {
        $user = $this->createUser();
        $actor = $this->createUser();
        $post = $this->createPost($user);
        $comment = $this->createComment($actor, $post);

        $this->assertCount(1, $user->getFlatFeed(), 'user flat feed has one item');
        $this->assertCount(1, $user->getNotifications(), 'user notifications has one item');
        $this->assertCount(0, $actor->getFlatFeed(), 'actor flat feed has 0 item');

        $this->assertDatabaseHas(
            config('activity-stream.tables.activities'),
            $this->findActivityEntry($post->getVerb(), $user, $post)
        );
        $this->assertDatabaseHas(
            config('activity-stream.tables.activities'),
            $this->findActivityEntry($comment->getVerb(), $actor, $comment, $post)
        );
        $this->assertDatabaseHas(
            config('activity-stream.tables.feeds'),
            $this->findFeedEntry('1', FeedTypes::FLAT, $user, $post)
        );
        $this->assertDatabaseHas(
            config('activity-stream.tables.feeds'),
            $this->findFeedEntry('2', FeedTypes::NOTIFICATION, $user, $comment)
        );

        // Delete Comment
        $comment->delete();

        $this->assertCount(1, $user->getFlatFeed(), 'user flat feed has one item');
        $this->assertCount(0, $user->getNotifications(), 'user notifications has 0 item');
        $this->assertCount(0, $actor->getFlatFeed(), 'actor flat feed has 0 item');

        $this->assertDatabaseHas(
            config('activity-stream.tables.activities'),
            $this->findActivityEntry($post->getVerb(), $user, $post)
        );
        $this->assertDatabaseMissing(
            config('activity-stream.tables.activities'),
            $this->findActivityEntry($comment->getVerb(), $actor, $comment, $post)
        );
        $this->assertDatabaseHas(
            config('activity-stream.tables.feeds'),
            $this->findFeedEntry('1', FeedTypes::FLAT, $user, $post)
        );
    }
}
