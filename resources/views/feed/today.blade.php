@extends('layouts.app')

@section('content')
	<div class="container">
		<h1>Today <span class="pull-right"><small>{{ $items->total() }}</small></span></h1>

		{{ $items->links() }}
		@each ('feed-item.partials.item--panel--w-feedlink', $items, 'item')
		{{ $items->links() }}
	</div>
@endsection