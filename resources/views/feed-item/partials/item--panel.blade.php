<div class="panel panel-default">
	<div class="panel-heading">
		<a href="{{ route('feed.item.show', [$item->feed, $item]) }}">{{ $item->title }}</a>

		@include('feed-item.partials.panel-ending')
	</div>
</div>