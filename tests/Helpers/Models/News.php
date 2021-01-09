<?php

namespace ThrottleStudio\ActivityStream\Tests\Helpers\Models;

use Illuminate\Database\Eloquent\Model;
use ThrottleStudio\ActivityStream\Contracts\StreamActivity;
use ThrottleStudio\ActivityStream\Traits\LogsStreamActivity;

class News extends Model implements StreamActivity
{
    use LogsStreamActivity;

    protected $fillable = [
        'user_id',
        'body',
    ];

    public function getVerb(): string
    {
        return 'news';
    }

    public function getActor(): Model
    {
        return $this->user;
    }

    public function getFeedOwner(): Model
    {
        return $this->user;
    }

    public function user()
    {
        return $this->belongsTo(Author::class, 'user_id');
    }
}
