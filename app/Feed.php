<?php

namespace App;

use App\FeedUpdate;
use App\Jobs\FetchFeed;
use App\Jobs\UpdateFeed;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Model;

class Feed extends Model
{
    protected $guarded = [];

    function users()
    {
    	return $this->belongsToMany(User::class);
    }

    function items()
    {
    	return $this->hasMany(FeedItem::class);
    }

    function updates()
    {
        return $this->hasMany(FeedUpdate::class);
    }

    function getXML()
    {
    	$client = new Client([]);

        try {
            $body = (string) $client->get($this->url)->getBody();
        } catch(GuzzleException $e) {
            return null;
        }

        $hash = md5($body);

        if($this->updates()->where('hash', $hash)->count() == 0) {
            $this->updates()->create([
                'hash' => $hash,
                'content' => $body
            ]);
        }

        $loaded = simplexml_load_string($body, null, LIBXML_NOCDATA | LIBXML_NOERROR);

    	return $loaded !== false ? $loaded : null;
    }

    function getUnreadCountAttribute()
    {
        if(auth()->user()) {
            return FeedItem::leftJoin('feed_item_user', 'feed_item_user.feed_item_id', 'feed_items.id')
                ->whereNull('feed_item_user.user_id')->where('feed_items.feed_id', $this->id)
                ->orWhere(function($query) {
                    $query->where('feed_item_user.user_id', '!=', auth()->id())
                    ->where('feed_items.feed_id', $this->id);
                })->orderBy('pub_date', 'desc')->count();
        }
        return null;
    }

    static function maybeCreateAndCreateJobs($data)
    {
        if(self::where('url', $data['url'])->count() !== 0) {
            return null;
        }

        return tap(self::create($data), function($feed) {
            FetchFeed::dispatch($feed);
            UpdateFeed::dispatch($feed)->delay(Carbon::now()->addMinutes(2));
        });
    }
}
