<?php

namespace ThrottleStudio\ActivityStream\Tests;

use ThrottleStudio\ActivityStream\Tests\TestCase;
use ThrottleStudio\ActivityStream\Enums\FeedTypes;

class CommandTest extends TestCase
{
    /** @test */
    public function create_custom_feed()
    {
        $this->artisan('activitystream:feed TrendingFeed')
            ->assertExitCode(0);
    }
}
