<?php

namespace App;

use App\Feed;
use App\FeedItem;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    function feeds()
    {
        return $this->belongsToMany(Feed::class, 'feed_user', 'user_id');
    }

    function groups()
    {
        return $this->hasMany(FeedGroup::class);
    }

    function readItems()
    {
        return $this->belongsToMany(FeedItem::class, 'feed_item_user', 'user_id');
    }

    function savedItems()
    {
        return $this->belongsToMany(FeedItem::class, 'saved_items', 'user_id');
    }

    function favouritedFeeds()
    {
        return $this->belongsToMany(Feed::class, 'favourite_feeds', 'user_id');
    }

    function getLatestItems($paginate = 50)
    {
        if($paginate !== false) {
            return FeedItem::whereIn('feed_id', $this->feeds->pluck('id'))->orderBy('pub_date', 'desc')->paginate($paginate);
        }
        return FeedItem::whereIn('feed_id', $this->feeds->pluck('id'))->orderBy('pub_date', 'desc')->get();
    }

    function unreadSubscriptionItems()
    {
        return FeedItem::leftJoin('feed_item_user', 'feed_item_user.feed_item_id', 'feed_items.id')
            ->whereNull('feed_item_user.user_id')->whereIn('feed_items.feed_id', $this->feeds()->pluck('id'))
            ->orWhere(function($query) {
                $query->where('feed_item_user.user_id', '!=', $this->id)
                ->whereIn('feed_items.feed_id', $this->feeds()->select('id')->get());
            })->orderBy('pub_date', 'desc');
    }
}
