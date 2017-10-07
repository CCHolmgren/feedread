<div class="panel panel-default">
	<div class="panel-heading">
		<a href="{{ route('feed.latest.item', [$item]) }}">{{ $item->title }}</a>
		<span class=""><a href="{{ route('feed.show', $item->feed) }}">{{ $item->feed->title }}</a></span>

		@include('feed-item.partials.panel-ending')
	</div>
</div>