<?php

return [

    /**
     * Database table names change these default names to prevent
     * any conflicts with existing tables.
     */
    'tables' => [
        'activities' => 'activities',
        'feeds' => 'feeds',
        'follows' => 'follows',
    ],

    /**
     * Default number of items to transfer over from the flat feed
     * to the timeline when an entity begins to follow another entity.
     */
    'follow_feed_count' => 200,

    /**
     * Default pagination values for each of the feed types.
     */
    'feed_pagination' => 20,

    /**
     * Verb to be used when a follow activity is created.
     */
    'follow_verb' => 'follow',
];
