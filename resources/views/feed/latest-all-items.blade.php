@extends('layouts.app')

@section('content')
<div class="container">
	<h1>All feed items</h1>
	{{ $items->links() }}
	@each ('feed-item.partials.item--latest-all--panel--w-feedlink', $items, 'item')
	{{ $items->links() }}
</div>
@endsection