<?php

namespace ThrottleStudio\ActivityStream\Tests\Helpers\Models;

use Illuminate\Database\Eloquent\Model;
use ThrottleStudio\ActivityStream\Contracts\StreamActivity;
use ThrottleStudio\ActivityStream\Traits\LogsStreamActivity;
use ThrottleStudio\ActivityStream\Tests\Helpers\Feeds\TrendingFeed;

class Tweet extends Model implements StreamActivity
{
    use LogsStreamActivity;

    protected $fillable = [
        'user_id',
        'body'
    ];

    public function getVerb(): string
    {
        return 'tweet';
    }

    public function getActor(): Model
    {
        return $this->user;
    }

    public function getFeedOwner(): Model
    {
        return $this->user;
    }

    public function addToCustomFeeds()
    {
        return [
            NonExistant::class,
            User::class,
            TrendingFeed::class,
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
