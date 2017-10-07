@extends('layouts.app')

@section('content')
<div class="container">
	<div class="panel panel-default feed-item">
		<div class="panel-heading">
			<div><a href="{{ route('feed.show', $item->feed) }}">{{ $item->feed->title }}</a></div>
			<h2 class="feed-item__headline">{{ $item->title }}</h2>

			@include('feed-item.partials.read-unread-forms')
			@if(auth()->user()->savedItems()->where('feed_item_id', $item->id)->count() == 0)
			<form class="inline-block" method="post" action="{{ route('feed.save-item', $item) }}">
				{{ csrf_field() }}
				<button class="btn btn-default" type="submit">Save item</button>
			</form>
			@else
			<form class="inline-block" method="post" action="{{ route('feed.unsave-item', $item) }}">
				{{ csrf_field() }}
				<button class="btn btn-default" type="submit">Unsave item</button>
			</form>
			@endif
			<a class="btn btn-default" href="{{ $item->link }}">Visit website</a>
		</div>
		<div class="panel-body">
			<div>
				@if($item->nextItem())
				<a class="btn btn-default" href="{{ route('feed.item.show', [$feed, $item->nextItem()]) }}">Next item</a>
				@endif

				@if($item->previousItem())
				<a class="pull-right btn btn-default" href="{{ route('feed.item.show', [$feed, $item->previousItem()]) }}">Previous item</a>
				@endif
			</div>

			<p>{{ $item->pub_date }} {{ $item->category }}</p>
			
			{!! $item->description !!}
		</div>
	</div>
</div>
@endsection