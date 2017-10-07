<?php

namespace App\Http\Controllers\Feed;

use App\Feed;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FeedSubscriptionController extends Controller
{
    function subscribe(Feed $feed)
    {
    	$feed->users()->attach(request()->user()->id);

    	return redirect()->back()
    		->with('status', $feed->name ? "Subscribed to {$feed->title}!" : 'Successfully subscribed!');
    }

    function unsubscribe(Feed $feed)
    {
    	$feed->users()->detach(request()->user()->id);

    	return redirect()->back()
    		->with('status', $feed->name ? "Unsubscribed from {$feed->title}!" : 'Successfully unsubscribed!');
    }

    function index()
    {
        return view('feed.subscriptions', [
            'subscriptions' => auth()->user()->feeds,
            'feeds' => Feed::all()
        ]);
    }
}
