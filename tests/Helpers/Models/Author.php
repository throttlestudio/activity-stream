<?php

namespace ThrottleStudio\ActivityStream\Tests\Helpers\Models;

use Illuminate\Database\Eloquent\Model;
use ThrottleStudio\ActivityStream\Traits\CanBeFollowed;
use ThrottleStudio\ActivityStream\Traits\HasFeeds;

class Author extends Model
{
    use CanBeFollowed;
    use HasFeeds;

    protected $fillable = [
        'name',
    ];
}
