<?php

namespace App\Http\Controllers\Feed;

use App\Feed;
use App\FeedItem;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FeedItemController extends Controller
{
    function show(Feed $feed, FeedItem $item)
    {
    	auth()->user()->readItems()->syncWithoutDetaching([$item->id => ['created_at' => Carbon::now()->format('Y-m-d H:i:s')]]);
    	
    	return view('feed-item.show', [
    		'feed' => $feed,
    		'item' => $item
    	]);
    }

    function subscriptionShow(FeedItem $item)
    {
    	auth()->user()->readItems()->syncWithoutDetaching([$item->id => ['created_at' => Carbon::now()->format('Y-m-d H:i:s')]]);
    	
    	return view('feed-item.subscription-show', [
    		'item' => $item
    	]);
    }

    function latestAllShow(FeedItem $item)
    {
    	auth()->user()->readItems()->syncWithoutDetaching([$item->id => ['created_at' => Carbon::now()->format('Y-m-d H:i:s')]]);
        
    	return view('feed-item.latest-all-show', [
    		'item' => $item
    	]);
    }

    function savedItems()
    {
        return view('feed-item.saved-items', [
            'items' => auth()->user()->savedItems
        ]);
    }

    function saveItem(FeedItem $item)
    {
        auth()->user()->savedItems()->syncWithoutDetaching([$item->id => ['created_at' => Carbon::now() ]]);

        return redirect()->back();
    }

    function unsaveItem(FeedItem $item)
    {
        auth()->user()->savedItems()->detach($item);

        return redirect()->back();
    }

    function today()
    {
        $items = FeedItem::whereDate('pub_date', Carbon::now()->format('Y-m-d'))->orderBy('pub_date', 'desc')->paginate(50);

        return view('feed.today', [
            'items' => $items
        ]);
    }
}
