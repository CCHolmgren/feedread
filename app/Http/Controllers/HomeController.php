<?php

namespace App\Http\Controllers;

use App\Feed;
use App\FeedItem;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $feed = new \SimplePie();
        $feed->set_feed_url(Feed::find(23)->url);
        $feed->enable_order_by_date(false);
        $feed->enable_cache(false);
        $feed->strip_htmltags(['script']);
        $success = $feed->init();
        $feed->handle_content_type();

        $items = $feed->get_items();

        foreach($items as $item) {
            dump($item->get_link(), $item->get_permalink(), $item->get_date(), $item->get_author(), $item->get_id(), $item->get_title(), $item->get_description(), $item->get_content(), $item->get_categories(), $item->get_enclosure()
        );
        }

        dd($feed->get_link(), $feed->get_title(), $feed->get_description(), $feed->get_author(), $feed->get_items());
        return view('home', [
            'feeds' => Feed::all(),
            'subscriptions' => auth()->user()->feeds ?? collect(),
            'groups' => auth()->user()->groups
        ]);
    }
}
