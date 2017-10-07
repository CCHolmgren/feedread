<div class="panel panel-default">
	<div class="panel-heading">
		<div><a href="{{ route('feed.show', $feed) }}">{{ $feed->title }}</a> <span class="pull-right">{{ $feed->items->count() }} items, {{ $feed->unreadCount }} unread</span></div>
	</div>
</div>