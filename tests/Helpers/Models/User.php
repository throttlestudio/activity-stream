<?php

namespace ThrottleStudio\ActivityStream\Tests\Helpers\Models;

use Illuminate\Database\Eloquent\Model;
use ThrottleStudio\ActivityStream\Traits\CanFollowAndBeFollowed;
use ThrottleStudio\ActivityStream\Traits\HasFeeds;

class User extends Model
{
    use CanFollowAndBeFollowed;
    use HasFeeds;

    protected $fillable = [
        'name'
    ];
}
