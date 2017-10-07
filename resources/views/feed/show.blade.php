@extends('layouts.app')

@section('content')
<div class="container">
	@if (session('status'))
	<div class="alert alert-success">
		{{ session('status') }}
	</div>
	@endif
	<h1><a href="{{ $feed->link }}">{{ $feed->title }}</a> <small>Last updated {{ $feed->updated }}</small></h1>
	<h2>
		<a href="{{ $feed->url }}">Feed url</a>

		<form class="inline-block pull-right" action="{{ route('feed.updateFeed', $feed) }}" method="post">
			{{ csrf_field() }}
			<button class="btn btn-default" type="submit">Update feed</button>
		</form>

		@if(auth()->user()->feeds->pluck('id')->contains($feed->id))
		<form class="inline-block pull-right" method="post" action="{{ route('feed.unsubscribe', $feed) }}">
			{{ csrf_field() }}
			<button class="btn btn-default" type="submit">Unsubscribe</button>
		</form>
		@else
		<form class="inline-block pull-right" method="post" action="{{ route('feed.subscribe', $feed) }}">
			{{ csrf_field() }}
			<button class="btn btn-default" type="submit">Subscribe</button>
		</form>
		@endif

		@if(auth()->user()->favouritedFeeds->pluck('id')->contains($feed->id))
		<form class="inline-block pull-right" method="post" action="{{ route('feed.defavourite', $feed) }}">
			{{ csrf_field() }}
			{{ method_field('DELETE') }}
			<button class="btn btn-default" type="submit">Unfavourite</button>
		</form>
		@else
		<form class="inline-block pull-right" method="post" action="{{ route('feed.favourite', $feed) }}">
			{{ csrf_field() }}
			<button class="btn btn-default" type="submit">Favourite</button>
		</form>
		@endif
	</h2>
	<div>
		{{ $feed->description }}
	</div>
	{{ $items->links() }}
	@each ('feed-item.partials.item--panel', $items, 'item')
	{{ $items->links() }}
</div>
@endsection