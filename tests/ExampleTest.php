<?php

namespace ThrottleStudio\LaravelActivityStream\Tests;

use Orchestra\Testbench\TestCase;
use ThrottleStudio\LaravelActivityStream\LaravelActivityStreamServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [LaravelActivityStreamServiceProvider::class];
    }

    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
