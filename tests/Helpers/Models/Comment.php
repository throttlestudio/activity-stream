<?php

namespace ThrottleStudio\ActivityStream\Tests\Helpers\Models;

use Illuminate\Database\Eloquent\Model;
use ThrottleStudio\ActivityStream\Contracts\StreamActivity;
use ThrottleStudio\ActivityStream\Traits\LogsStreamActivity;

class Comment extends Model implements StreamActivity
{
    use LogsStreamActivity;

    protected $fillable = [
        'user_id',
        'post_id',
        'body'
    ];

    public function addToFlatFeed()
    {
        return false;
    }

    public function getVerb(): string
    {
        return 'comment';
    }

    public function getActor(): Model
    {
        return $this->user;
    }

    public function getFeedOwner(): Model
    {
        return $this->user;
    }

    public function getTarget()
    {
        return $this->post;
    }

    public function getNotify()
    {
        return $this->post->user;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
}
