<?php

namespace ThrottleStudio\ActivityStream\Tests;

class CommandTest extends TestCase
{
    /** @test */
    public function create_custom_feed()
    {
        $this->artisan('stream:feed TrendingFeed')
            ->assertExitCode(0);
    }
}
