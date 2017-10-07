@extends('layouts.app')

@section('content')
	<div class="container">
		<h1>Your latest read items</h1>
		<p>{{ $items->total() }} read items</p>
		{{ $items->links() }}
		@each('feed-item.partials.item--panel--w-feedlink', $items, 'item', 'feed.partials.read-items--no-items')
		{{ $items->links() }}
	</div>
@endsection