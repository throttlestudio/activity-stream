<?php

namespace ThrottleStudio\ActivityStream\Enums;

use MyCLabs\Enum\Enum;

class FeedEventTypes extends Enum
{
    const CREATED = 'created';
    const DELETED = 'deleted';
}
