<?php

namespace ThrottleStudio\ActivityStream;

use Illuminate\Support\Facades\Facade;

/**
 * @see \ThrottleStudio\ActivityStream\Skeleton\SkeletonClass
 */
class ActivityStreamFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-activity-stream';
    }
}
