<?php

namespace App\Http\Controllers\Feed;

use App\Feed;
use App\FeedItem;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ItemReadController extends Controller
{
    function mark(Feed $feed, FeedItem $item)
    {
    	auth()->user()->readItems()->attach($item->id, ['created_at' => Carbon::now()->format('Y-m-d H:i:s')]);

    	return redirect()->back();
    }

    function unmark(Feed $feed, FeedItem $item)
    {
    	auth()->user()->readItems()->detach($item->id);

    	return redirect()->back();	
    }

    function index()
    {
    	return view('feed.read', [
    		'items' => auth()->user()->readItems()->orderBy('created_at', 'asc')->paginate(50)
    	]);
    }
}
