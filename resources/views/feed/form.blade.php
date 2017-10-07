<form action="{{ route('feed.store') }}" method="post">
	{{ csrf_field() }}
	<div class="form-group">
		<input class="form-control" type="url" name="url" placeholder="Feed url">
	</div>
	<div class="form-group">
		<button class="btn btn-default" type="submit">Save feed</button>
	</div>
</form>