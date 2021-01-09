<?php

namespace ThrottleStudio\ActivityStream\Tests;

use ThrottleStudio\ActivityStream\Tests\TestCase;
use ThrottleStudio\ActivityStream\Enums\FeedTypes;
use ThrottleStudio\ActivityStream\Models\Activity;
use ThrottleStudio\ActivityStream\Models\Feed;
use ThrottleStudio\ActivityStream\Models\Follow;

class FollowFeedTest extends TestCase
{
    /** @test */
    public function user_follows_author_with_empty_feed_then_unfollows()
    {
        $user = $this->createUser();
        $author = $this->createAuthor();

        $follow = $user->follow($author);

        $this->assertTrue($user->isFollowing($author), 'user following author');
        $this->assertEquals(1, $user->followingCount, 'user is following 1 item');
        $this->assertTrue($author->isFollowedBy($user), 'author is followedBy user true');
        $this->assertEquals(1, $author->followersCount, 'author has 1 follower');
        $this->assertCount(1, $author->getNotifications(), 'author notifications has one item');

        $this->assertCount(1, Follow::get(), 'follow db has 1 item');
        $this->assertCount(1, Activity::get(), 'activity db has 1 item');
        $this->assertCount(1, Feed::get(), 'feed db has 1 item');

        $this->assertDatabaseHas(
            config('activity-stream.tables.follows'),
            $this->findFollowEntry($user, $author)
        );
        $this->assertDatabaseHas(
            config('activity-stream.tables.activities'),
            $this->findActivityEntry(config('activity-stream.follow_verb'), $user, $follow, $author)
        );
        $this->assertDatabaseHas(
            config('activity-stream.tables.feeds'),
            $this->findFeedEntry('1', FeedTypes::NOTIFICATION, $author)
        );

        // Unfollow
        $user->unfollow($author);

        $this->assertFalse($user->isFollowing($author), 'user not-following author');
        $this->assertEquals(0, $user->followingCount, 'user is following 0 item');
        $this->assertFalse($author->isFollowedBy($user), 'author is followedBy user false');
        $this->assertEquals(0, $author->followersCount, 'author has 0 follower');
        $this->assertCount(0, $author->getNotifications(), 'author notifications has 0 item');

        $this->assertCount(0, Follow::get(), 'follow db has 0 item');
        $this->assertCount(0, Activity::get(), 'activity db has 0 item');
        $this->assertCount(0, Feed::get(), 'feed db has 0 item');

        $this->assertDatabaseMissing(
            config('activity-stream.tables.follows'),
            $this->findFollowEntry($user, $author)
        );
        $this->assertDatabaseMissing(
            config('activity-stream.tables.activities'),
            $this->findActivityEntry(config('activity-stream.follow_verb'), $user, $follow, $author)
        );
        $this->assertDatabaseMissing(
            config('activity-stream.tables.feeds'),
            $this->findFeedEntry('1', FeedTypes::NOTIFICATION, $author)
        );
    }

    /** @test */
    public function user_follows_author_with_items_then_unfollows()
    {
        $user = $this->createUser();
        $author = $this->createAuthor();
        $this->createNews($author);
        $this->createNews($author);

        $user->follow($author);

        $this->assertCount(2, $author->getFlatFeed(), 'author feed has 2 item');
        $this->assertCount(1, $author->getNotifications(), 'author notifications has one item');
        $this->assertCount(2, $user->getTimeline(), 'user timeline has 2 item');

        $this->assertCount(1, Follow::get(), 'follow db has 1 item');
        $this->assertCount(3, Activity::get(), 'activity db has 3 item');
        $this->assertCount(5, Feed::get(), 'feed db has 5 item');
        $follows = Activity::where('verb', 'news')->get();

        $this->assertDatabaseHas(
            config('activity-stream.tables.feeds'),
            $this->findFeedEntry('3', FeedTypes::NOTIFICATION, $author)
        );
        $this->assertDatabaseHas(
            config('activity-stream.tables.feeds'),
            $this->findFeedEntry($follows->first()->id, FeedTypes::TIMELINE, $user)
        );
        $this->assertDatabaseHas(
            config('activity-stream.tables.feeds'),
            $this->findFeedEntry($follows->last()->id, FeedTypes::TIMELINE, $user)
        );

        $user->unfollow($author);

        $this->assertCount(2, $author->getFlatFeed(), 'author feed has 2 item');
        $this->assertCount(0, $author->getNotifications(), 'author notifications has one item');
        $this->assertCount(0, $user->getTimeline(), 'user timeline has 0 item');

        $this->assertCount(0, Follow::get(), 'follow db has 0 item');
        $this->assertCount(2, Activity::get(), 'activity db has 2 item');
        $this->assertCount(2, Feed::get(), 'feed db has 2 item');
    }

    /** @test */
    public function user_follows_author_author_adds_post_then_deletes_post()
    {
        $user = $this->createUser();
        $author = $this->createAuthor();
        $this->createNews($author);
        $this->createNews($author);

        $user->follow($author);

        $article = $this->createNews($author);

        $this->assertCount(3, $author->getFlatFeed(), 'author feed has 3 item');
        $this->assertCount(1, $author->getNotifications(), 'author notifications has one item');
        $this->assertCount(3, $user->getTimeline(), 'user timeline has 3 item');

        $this->assertCount(1, Follow::get(), 'follow db has 1 item');
        $this->assertCount(4, Activity::get(), 'activity db has 4 item');
        $this->assertCount(7, Feed::get(), 'feed db has 7 item');

        // Delete article
        $article->delete();

        $this->assertCount(2, $author->getFlatFeed(), 'author feed has 2 item');
        $this->assertCount(1, $author->getNotifications(), 'author notifications has one item');
        $this->assertCount(2, $user->getTimeline(), 'user timeline has 2 item');

        $this->assertCount(1, Follow::get(), 'follow db has 1 item');
        $this->assertCount(3, Activity::get(), 'activity db has 3 item');
        $this->assertCount(5, Feed::get(), 'feed db has 5 item');
    }

    /** @test */
    public function user_follows_author_author_adds_post_then_unfollows()
    {
        $user = $this->createUser();
        $author = $this->createAuthor();
        $this->createNews($author);
        $this->createNews($author);

        $user->follow($author);

        $article = $this->createNews($author);

        $this->assertCount(3, $author->getFlatFeed(), 'author feed has 3 item');
        $this->assertCount(1, $author->getNotifications(), 'author notifications has one item');
        $this->assertCount(3, $user->getTimeline(), 'user timeline has 3 item');

        $this->assertCount(1, Follow::get(), 'follow db has 1 item');
        $this->assertCount(4, Activity::get(), 'activity db has 4 item');
        $this->assertCount(7, Feed::get(), 'feed db has 7 item');

        // Delete article
        $user->unfollow($author);

        $this->assertCount(3, $author->getFlatFeed(), 'author feed has 3 item');
        $this->assertCount(0, $author->getNotifications(), 'author notifications has one item');
        $this->assertCount(0, $user->getTimeline(), 'user timeline has 2 item');

        $this->assertCount(0, Follow::get(), 'follow db has 0 item');
        $this->assertCount(3, Activity::get(), 'activity db has 3 item');
        $this->assertCount(3, Feed::get(), 'feed db has 3 item');
    }

    /** @test */
    public function user_follows_another_user()
    {
        $userA = $this->createUser();
        $userB = $this->createUser();

        $userA->toggleFollow($userB);

        $this->assertTrue($userA->isFollowing($userB), 'userA follows userB');
        $this->assertFalse($userA->isFollowedBy($userB), 'userA is not followed by userB');
        $this->assertEquals(1, $userA->followingCount, 'userA follows 1');
        $this->assertEquals(0, $userA->followersCount, 'userA followers 0');

        $this->assertFalse($userB->isFollowing($userA), 'userB is not following userA');
        $this->assertTrue($userB->isFollowedBy($userA), 'userB is followed by userA');
        $this->assertEquals(0, $userB->followingCount, 'userB follows 0');
        $this->assertEquals(1, $userB->followersCount, 'userB followers 1');

        $userA->toggleFollow($userB);

        $this->assertFalse($userA->isFollowing($userB), 'userA is not following userB');
        $this->assertFalse($userA->isFollowedBy($userB), 'userA is not followed by userB');
        $this->assertEquals(0, $userA->followingCount, 'userA follows 0');
        $this->assertEquals(0, $userA->followersCount, 'userA followers 0');

        $this->assertFalse($userB->isFollowing($userA), 'userB is not following userA');
        $this->assertFalse($userB->isFollowedBy($userA), 'userB is not followed by userA');
        $this->assertEquals(0, $userB->followingCount, 'userB follows 0');
        $this->assertEquals(0, $userB->followersCount, 'userB followers 0');
    }
}
