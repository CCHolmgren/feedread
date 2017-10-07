<?php

namespace App\Http\Controllers\Feed;

use App\Feed;
use Carbon\Carbon;
use App\Jobs\FetchFeed;
use App\Jobs\UpdateFeed;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FeedController extends Controller
{
    function store()
    {
        $feed = Feed::maybeCreateAndCreateJobs(request()->all());
    	if($feed == null) {
    		return redirect()->back();
    	}

    	return redirect()->route('home')->with('status', 'Feed added!');
    }

    function show(Feed $feed)
    {
    	return view('feed.show', [
    		'feed' => $feed,
            'items' => $feed->items()->orderBy('pub_date', 'desc')->paginate(50)
    	]);
    }

    function updateFeed(Feed $feed)
    {
    	UpdateFeed::dispatch($feed, false, true);

        return redirect()->route('feed.show', $feed);
    }

    function updateAll()
    {
        Feed::all()->each(function($feed) {
            UpdateFeed::dispatch($feed);
        });

        return redirect()->route('home');
    }

    function index()
    {
        return view('feed.index', [
            'feeds' => Feed::paginate(50)
        ]);
    }

    function favourite(Feed $feed)
    {
        auth()->user()->favouritedFeeds()->syncWithoutDetaching([$feed->id => ['created_at' => Carbon::now()]]);

        return redirect()->back();
    }

    function defavourite(Feed $feed)
    {
        auth()->user()->favouritedFeeds()->detach($feed->id);

        return redirect()->back();
    }

    function favouriteFeeds()
    {
        return view('feed.favourite', [
            'feeds' => auth()->user()->favouritedFeeds
        ]);
    }
}
