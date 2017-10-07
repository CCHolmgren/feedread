@extends('layouts.app')

@section('content')
	<div class="container">
		<h1>Feeds</h1>
		{{ $feeds->links() }}
		@each ('feed.partials.show', $feeds, 'feed')
		{{ $feeds->links() }}
	</div>
@endsection