<?php

namespace App\Http\Controllers\OPML;

use App\Feed;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    function import()
    {
    	$file = request()->file('opml-file');

    	$data = simplexml_load_file($file->path());

    	$json = json_decode(json_encode($data));

    	$groups = object_get($json, 'body.outline');

    	$groupsArray = [];

    	foreach($groups as $group) {
    		$feeds = [];

    		if(is_array(object_get($group, 'outline'))) {
	    		foreach(object_get($group, 'outline') as $feed) {
	    			$feed = object_get($feed, '@attributes');

	    			$feeds[] = [
	    				'url' => object_get($feed, 'xmlUrl'),
	    				'link' => object_get($feed, 'htmlUrl'), 
	    				'title' => object_get($feed, 'title')
	    			];
	    		}
    		} else {
    			$feed = object_get($group, 'outline.@attributes');

    			$feeds[] = [
    				'url' => object_get($feed, 'xmlUrl'),
    				'link' => object_get($feed, 'htmlUrl'), 
    				'title' => object_get($feed, 'title')
    			];
    		}


    		$groupsArray[] = [
    			'name' => object_get($group, '@attributes.title'),
    			'feeds' => $feeds
    		];
    	}

    	foreach($groupsArray as $group) {
    		$feeds = $group['feeds'];

            $group = auth()->user()->groups()->firstOrCreate(['name' => $group['name']]);

	    	foreach($feeds as $feed) {
	    		$foundFeed = Feed::maybeCreateAndCreateJobs($feed);
                if($foundFeed == null) {
                    $foundFeed = Feed::where('url', $feed['url'])->first();
                }
    			$group->feeds()->syncWithoutDetaching($foundFeed);
	    	}
    	}

    	return redirect()->route('home');
    }
}
