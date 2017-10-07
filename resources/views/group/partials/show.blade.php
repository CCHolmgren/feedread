<div class="panel panel-default">
	<div class="panel-heading">
		<div><a href="{{ route('group.show', $group) }}">{{ $group->name }}</a> <span class="pull-right">{{ $group->feeds()->count() }} feeds</span></div>
	</div>
</div>