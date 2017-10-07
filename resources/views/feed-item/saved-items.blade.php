@extends('layouts.app')

@section('content')
<div class="container">
	<h1>Saved items</h1>
	@each ('feed-item.partials.item--panel', $items, 'item')
</div>
@endsection