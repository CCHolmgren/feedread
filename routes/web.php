<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

/**
|--------------------------------------------------------------------------
| Import opml file
|--------------------------------------------------------------------------
| 
| 
*/

Route::post('import', 'OPML\ImportController@import')->name('opml.import');

/**
|--------------------------------------------------------------------------
| Item, group and feed routes
|--------------------------------------------------------------------------
| 
| Feeds should be organized into groups, it can be in multiple groups
| so there must be a table to store that for each user
| 
| Implement a today view, that lists all items that were created today
| based on the server date, not the user to begin with
| 
| Filters for items, such as display or hide read items from the feed
| 
| Favourite feeds with their items into a special view
| 
| Saved items
| 
| Read later for items that you want to store, but gets marked as read, 
| still hanging on in the list so you can se what you marked read later, later
| 
| Mark as read for all items in a feed or group
| 
| Renaming feeds, such as renaming Ars Technica into Ras Icanchte
| for that specific user
| 
| 
| 
| 
| 
| 
| 
| 
| 
| 
| 
| 
| 
*/

Route::prefix('groups')->namespace('Group')->group(function() {
	Route::get('/', 'GroupController@index')->name('group.index');
	Route::get('{group}', 'GroupController@show')->name('group.show');
	Route::post('{group}', 'GroupController@update')->name('group.update');
	Route::delete('{group}', 'GroupController@delete')->name('group.delete');
	Route::post('/', 'GroupController@store')->name('group.store');
});

Route::get('subscriptions', 'Feed\FeedSubscriptionController@index')->name('feed.subscriptions');
Route::get('subscriptions/latest', 'Feed\LatestController@index')->name('feed.subscriptions.latest');
Route::get('latest', 'Feed\LatestController@all')->name('feed.latest');

Route::get('subscriptions/{item}', 'Feed\FeedItemController@subscriptionShow')->name('feed.subscriptions.item');
Route::get('latest/{item}', 'Feed\FeedItemController@latestAllshow')->name('feed.latest.item');

Route::get('read', 'Feed\ItemReadController@index')->name('item.read');

Route::post('update-all', 'Feed\FeedController@updateAll')->name('feed.updateAll');

Route::prefix('feeds')->namespace('Feed')->group(function() {
	Route::get('/', 'FeedController@index')->name('feed.index');
	Route::post('/', 'FeedController@store')->name('feed.store');
	//Route::post('update-all', 'Feed\FeedController@updateAll')->name('feed.updateAll');

	Route::get('today', 'FeedItemController@today')->name('feed.today');

	Route::get('saved-items', 'FeedItemController@savedItems')->name('feed.saved-items');
	Route::post('saved-items/{item}', 'FeedItemController@saveItem')->name('feed.save-item');
	Route::post('saved-items/{item}/unsave', 'FeedItemController@unsaveItem')->name('feed.unsave-item');
	Route::get('favourite', 'FeedController@favouriteFeeds')->name('feed.favourite-feeds');

	Route::prefix('{feed}')->group(function() {
		Route::get('/', 'FeedController@show')->name('feed.show');

		Route::post('subscribe', 'FeedSubscriptionController@subscribe')->name('feed.subscribe');
		Route::post('unsubscribe', 'FeedSubscriptionController@unsubscribe')->name('feed.unsubscribe');

		Route::post('favourite', 'FeedController@favourite')->name('feed.favourite');
		Route::delete('favourite', 'FeedController@defavourite')->name('feed.defavourite');

		Route::post('update-feed', 'FeedController@updateFeed')->name('feed.updateFeed');

		Route::prefix('items')->group(function() {
			Route::get('{item}', 'FeedItemController@show')->name('feed.item.show');
			Route::post('{item}/read', 'ItemReadController@mark')->name('feed.item.mark-read');
			Route::post('{item}/unread', 'ItemReadController@unmark')->name('feed.item.mark-unread');
		});
	});
});
