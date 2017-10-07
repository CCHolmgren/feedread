@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    Unread subscription items: {{ auth()->user()->unreadSubscriptionItems()->count() }}

                    <h2>Add a new feed</h2>
                    @include('feed.form')

                    <form method="post" action="{{ route('feed.updateAll') }}">
                        {{ csrf_field() }}
                        <button class="btn btn-default" type="submit">Update all</button>
                    </form>

                    <hr>

                    <form method="post" action="{{ route('opml.import') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="opml-file">Upload OPML file</label>
                            <input class="form-control" type="file" id="opml-file" name="opml-file">
                        </div>

                        <button class="btn btn-default" type="submit">Import file</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
