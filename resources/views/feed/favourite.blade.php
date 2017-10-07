@extends('layouts.app')

@section('content')
	<div class="container">
		<h1>Favourite feeds</h1>
		@each ('feed.partials.show', $feeds, 'feed')
	</div>
@endsection