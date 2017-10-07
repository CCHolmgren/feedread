<div class="panel panel-default">
	<div class="panel-heading">
		<a href="{{ route('feed.item.show', [$item->feed, $item]) }}">{{ $item->title }}</a>
		|
		<span style="opacity: 0.8;"><a style="color: #222;" href="{{ route('feed.show', $item->feed) }}">{{ $item->feed->title }}</a></span>

		@include('feed-item.partials.panel-ending')
	</div>
</div>