@extends('layouts.app')

@section('content')
	<div class="container">
		<h1>Groups</h1>
		<form method="post" action="{{ route('group.store') }}">
			{{ csrf_field() }}
			<div class="form-group">
				<input class="form-control" type="text" name="name" placeholder="Group name">
			</div>
			<div class="row">
			@foreach ($feeds as $feed)
				<div class="col-xs-12 col-sm-6 col-md-4">
					<label><input type="checkbox" name="feed_id[]" value="{{ $feed->id }}"> {{ $feed->title }}</label>
				</div>
			@endforeach
			</div>
			<div class="form-group">
				<button class="btn btn-default" type="submit">Save group</button>
			</div>
		</form>
		@each ('group.partials.show', $groups, 'group')
	</div>
@endsection