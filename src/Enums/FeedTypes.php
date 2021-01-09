<?php

namespace ThrottleStudio\ActivityStream\Enums;

use MyCLabs\Enum\Enum;

class FeedTypes extends Enum
{
    const FLAT = 'flat';
    const TIMELINE = 'timeline';
    const NOTIFICATION = 'notification';
}
