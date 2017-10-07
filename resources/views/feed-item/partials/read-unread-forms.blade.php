@if(auth()->user()->readItems()->where('feed_item_id', $item->id)->count() == 0)
<form class="inline-block pull-right" method="post" action="{{ route('feed.item.mark-read', [$item->feed, $item]) }}">
	{{ csrf_field() }}
	<button class="btn btn-default" type="submit">Mark as read</button>
</form>
@else
<form class="inline-block pull-right" method="post" action="{{ route('feed.item.mark-unread', [$item->feed, $item]) }}">
	{{ csrf_field() }}
	<button class="btn btn-default" type="submit">Mark as unread</button>
</form>
@endif