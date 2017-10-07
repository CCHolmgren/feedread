@extends('layouts.app')

@section('content')
	<div class="container">
		<h1>{{ $group->name }}</h1>
		<form method="post" action="{{ route('group.update', $group) }}">
			{{ csrf_field() }}
			<div class="form-group">
				<input class="form-control" type="text" name="name" placeholder="Group name" value="{{ $group->name }}" required>
			</div>
			<div class="row">
			@foreach ($feeds as $feed)
				<div class="col-xs-12 col-sm-6 col-md-4">
					<label><input type="checkbox" name="feed_id[]" value="{{ $feed->id }}" @if($group->feeds->pluck('id')->contains($feed->id)) checked="checked" @endif> {{ $feed->title }}</label>
				</div>
			@endforeach
			</div>
			<div class="form-group">
				<button class="btn btn-default" type="submit">Save group</button>
			</div>
		</form>
		<form method="post" action="{{ route('group.delete', $group) }}">
			{{ csrf_field() }}
			{{ method_field('DELETE') }}
			<div class="form-group">
				<button class="btn btn-danger" type="submit">Delete</button>
			</div>
		</form>

		@each ('feed.partials.show', $group->feeds, 'feed')
	</div>
@endsection