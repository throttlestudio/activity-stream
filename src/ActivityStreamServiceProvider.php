<?php

namespace ThrottleStudio\ActivityStream;

use Illuminate\Support\ServiceProvider;
use ThrottleStudio\ActivityStream\Console\Commands\FeedMakeCommand;

class ActivityStreamServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // Publish configuration
        $this->publishes([
            __DIR__.'/../config/activity-stream.php' => config_path('activity-stream.php'),
        ], 'config');

        if ($this->app->runningInConsole()) {
            if (! class_exists('CreateActivityStreamTables')) {
                $this->publishes([
                    __DIR__.'/../database/migrations/create_activity_stream_tables.php' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_activity_stream_tables.php'),
                ], 'migrations');
            }

            // Registering package commands.
            $this->commands([
                FeedMakeCommand::class
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/activity-stream.php', 'activity-stream');

        // Register event service provider
        $this->app->register(ActivityStreamEventServiceProvider::class);

        // Register the main class to use with the facade
        $this->app->singleton('activity-stream', function () {
            return new ActivityStream;
        });
    }
}
