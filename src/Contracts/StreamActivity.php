<?php

namespace ThrottleStudio\ActivityStream\Contracts;

use Illuminate\Database\Eloquent\Model;

interface StreamActivity
{
    /**
     * Get the verb that represents this activity
     *
     * @return string;
     */
    public function getVerb(): string;

    /**
     * Get the actor that represents this activity
     *
     * @return \Illuminate\Database\Eloquent\Model;
     */
    public function getActor(): Model;

    /**
     * Get the owner that will have this activity logged too
     *
     * @return \Illuminate\Database\Eloquent\Model;
     */
    public function getFeedOwner(): Model;
}
