@extends('layouts.app')

@section('content')
<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading">
			<div><a href="{{ route('feed.show', $item->feed) }}">{{ $item->feed->title }}</a></div>
			<h2>{{ $item->title }}</h2>

			@include('feed-item.partials.read-unread-forms')
			
			<a class="btn btn-default" href="{{ $item->link }}">Visit website</a>
		</div>
		<div class="panel-body">
			<div>
				@if($item->nextItemAll())
				<a class="btn btn-default" href="{{ route('feed.latest.item', [$item->nextItemAll()]) }}">Next item</a>
				@endif

				@if($item->previousItemAll())
				<a class="pull-right btn btn-default" href="{{ route('feed.latest.item', [$item->previousItemAll()]) }}">Previous item</a>
				@endif
			</div>

			<p>{{ $item->pub_date }} {{ $item->category }}</p>
			
			{!! $item->description !!}
		</div>
	</div>
</div>
@endsection