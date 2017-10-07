@include('feed-item.partials.read-unread-forms')
<p>{{ $item->pub_date }} {{ $item->category }}</p>


@if(auth()->user()->savedItems()->where('feed_item_id', $item->id)->count() == 0)
<form method="post" action="{{ route('feed.save-item', $item) }}">
	{{ csrf_field() }}
	<button class="btn btn-default" type="submit">Save item</button>
</form>
@else
<form method="post" action="{{ route('feed.unsave-item', $item) }}">
	{{ csrf_field() }}
	<button class="btn btn-default" type="submit">Unsave item</button>
</form>
@endif