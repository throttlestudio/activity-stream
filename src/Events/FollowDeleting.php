<?php

namespace ThrottleStudio\ActivityStream\Events;

use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use ThrottleStudio\ActivityStream\Models\Follow;

class FollowDeleting
{
    use Dispatchable;
    use SerializesModels;

    /**
     * The follow instance.
     *
     * @var \ThrottleStudio\ActivityStream\Models\Follow
     */
    public $follow;

    /**
     * Create a new event instance.
     *
     * @param  \ThrottleStudio\ActivityStream\Models\Follow  $follow
     * @return void
     */
    public function __construct(Follow $follow)
    {
        $this->follow = $follow;
    }
}
