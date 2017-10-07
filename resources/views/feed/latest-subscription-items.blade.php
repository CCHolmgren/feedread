@extends('layouts.app')

@section('content')
<div class="container">
	<h1>Your latest subscription items</h1>
	<div class="row">
		<div class="col-xs-12">Subscriptions: {{ count($subscriptions) }} 
			<a class="btn btn-default pull-right" href="{{ route('feed.subscriptions') }}">Manage subscriptions</a>
		</div>
	</div>
	<div>Total: {{ $items->total() }}, read: {{ $items->total() - auth()->user()->unreadSubscriptionItems()->count() }}</div>
	{{ $items->links() }}
	@each ('feed-item.partials.item--subscription--panel--w-feedlink', $items, 'item')
	{{ $items->links() }}
</div>
@endsection