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
        return view('home', [
            'feeds' => Feed::all(),
            'subscriptions' => auth()->user()->feeds ?? collect(),
            'groups' => auth()->user()->groups
        ]);
    }
}
