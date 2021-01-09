<?php

namespace ThrottleStudio\ActivityStream\Tests;

use CreateActivityStreamTables;
use ThrottleStudio\ActivityStream\Tests\Helpers\Models\User;
use ThrottleStudio\ActivityStream\ActivityStreamServiceProvider;
use ThrottleStudio\ActivityStream\Tests\Helpers\Models\Author;
use ThrottleStudio\ActivityStream\Tests\Helpers\Models\Comment;
use ThrottleStudio\ActivityStream\Tests\Helpers\Models\News;
use ThrottleStudio\ActivityStream\Tests\Helpers\Models\Post;
use ThrottleStudio\ActivityStream\Tests\Helpers\Models\Tweet;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Load package service provider.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [ActivityStreamServiceProvider::class];
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->loadMigrationsFrom(__DIR__ . '/Helpers/migrations');

        include_once(__DIR__  . '/../database/migrations/create_activity_stream_tables.php');

        (new CreateActivityStreamTables())->up();
    }

    public function createUser()
    {
        return User::create(['name' => 'John Smith']);
    }

    public function createAuthor()
    {
        return Author::create(['name' => 'Steven King']);
    }

    public function createPost(User $user)
    {
        return Post::create([
            'user_id' => $user->id,
            'body' => 'some message'
        ]);
    }

    public function createNews(Author $author)
    {
        return News::create([
            'user_id' => $author->id,
            'body' => 'some message'
        ]);
    }

    public function createComment(User $user, Post $post)
    {
        return Comment::create([
            'user_id' => $user->id,
            'post_id' => $post->id,
            'body' => 'some message'
        ]);
    }

    public function createTweet(User $user)
    {
        return Tweet::create([
            'user_id' => $user->id,
            'body' => 'some message'
        ]);
    }

    public function findActivityEntry($verb, $actor, $object, $target = null)
    {
        return $target ? [
            'verb' => $verb,
            'actor_id' => $actor->id,
            'actor_type' => $actor->getMorphClass(),
            'object_id' => $object->id,
            'object_type' => $object->getMorphClass(),
            'target_id' => $target->id,
            'target_type' => $target->getMorphClass(),
        ] :
        [
            'verb' => $verb,
            'actor_id' => $actor->id,
            'actor_type' => $actor->getMorphClass(),
            'object_id' => $object->id,
            'object_type' => $object->getMorphClass(),
        ];
    }

    public function findFeedEntry($activity_id, $feedType, $owner)
    {
        return [
            'activity_id' => $activity_id,
            'type' => $feedType,
            'owner_id' => $owner->id,
            'owner_type' => $owner->getMorphClass(),
        ];
    }

    public function findFollowEntry($follower, $followable)
    {
        return [
            'follower_id' => $follower->id,
            'follower_type' => $follower->getMorphClass(),
            'followable_id' => $followable->id,
            'followable_type' => $followable->getMorphClass(),
        ];
    }
}
