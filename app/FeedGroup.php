<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FeedGroup extends Model
{
    protected $guarded = [];

    function feeds()
    {
    	return $this->belongsToMany(Feed::class, 'feed_group_feed');
    }

    function user()
    {
    	return $this->belongsTo(User::class);
    }
}
