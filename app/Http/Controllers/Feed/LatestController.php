<?php

namespace App\Http\Controllers\Feed;

use App\FeedItem;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LatestController extends Controller
{
    function index()
    {
    	return view('feed.latest-subscription-items', [
    		'items' => auth()->user()->getLatestItems(),
    		'subscriptions' => auth()->user()->feeds
    	]);
    }

    function all()
    {
    	return view('feed.latest-all-items', [
    		'items' => FeedItem::orderBy('pub_date', 'desc')->paginate(50)
    	]);
    }
}
