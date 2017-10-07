@extends('layouts.app')

@section('content')
<div class="container">
	@if (session('status'))
	<div class="alert alert-success">
		{{ session('status') }}
	</div>
	@endif

	<h1>Manage subscriptions</h1>

	<div class="row">
		<div class="col-lg-6">
			<h2>Subscriptions <span class="pull-right">{{ count($subscriptions) }}</span></h2>

			@foreach($subscriptions as $feed)
			<div class="panel panel-default">
				<div class="panel-heading">
					<a href="{{ route('feed.show', $feed) }}">{{ $feed->title ?? $feed->url }}</a> Updated at {{ $feed->updated }}

					<div>
						@if($subscriptions->pluck('id')->contains($feed->id))
						<form class="inline-block" method="post" action="{{ route('feed.unsubscribe', $feed) }}">
							{{ csrf_field() }}
							<button class="btn btn-default" type="submit">Unsubscribe</button>
						</form>
						@else
						<form class="inline-block" method="post" action="{{ route('feed.subscribe', $feed) }}">
							{{ csrf_field() }}
							<button class="btn btn-default" type="submit">Subscribe</button>
						</form>
						@endif
					</div>
				</div>
			</div>
			@endforeach	
		</div>
		<div class="col-lg-6">
			<h2>All feeds <span class="pull-right">{{ count($feeds) }}</span></h2>
			@foreach($feeds as $feed)
			<div class="panel panel-default">
				<div class="panel-heading">
					<a href="{{ route('feed.show', $feed) }}">{{ $feed->title ?? $feed->url }}</a> Updated at {{ $feed->updated }} 

					<div>
						@if($subscriptions->pluck('id')->contains($feed->id))
						<form class="inline-block" method="post" action="{{ route('feed.unsubscribe', $feed) }}">
							{{ csrf_field() }}
							<button class="btn btn-default" type="submit">Unsubscribe</button>
						</form>
						@else
						<form class="inline-block" method="post" action="{{ route('feed.subscribe', $feed) }}">
							{{ csrf_field() }}
							<button class="btn btn-default" type="submit">Subscribe</button>
						</form>
						@endif
					</div>
				</div>
			</div>
			@endforeach	
		</div>
	</div>
</div>
@endsection