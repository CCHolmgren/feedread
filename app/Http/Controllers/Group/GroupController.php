<?php

namespace App\Http\Controllers\Group;

use App\Feed;
use App\FeedGroup;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    function index()
    {
    	return view('group.index', [
    		'groups' => auth()->user()->groups,
    		'feeds' => Feed::all()
    	]);
    }

    function show(FeedGroup $group)
    {
    	return view('group.show', [
    		'group' => $group,
    		'feeds' => Feed::all()
    	]);
    }

    function store()
    {
    	$group = auth()->user()->groups()->create([
    		'name' => request('name')
    	]);

    	$group->feeds()->sync(request('feed_id'));

    	return redirect()->route('group.show', $group);
    }

    function update(FeedGroup $group)
    {
    	$group->fill(['name' => request('name')])->save();

    	$group->feeds()->sync(request('feed_id'));

    	return redirect()->route('group.show', $group);	
    }

    function delete(FeedGroup $group)
    {
    	$group->feeds()->sync([]);
    	$group->delete();

    	return redirect()->route('group.index');
    }
}
