<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ route('home') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        
                        
                        <li><a href="{{ route('feed.index') }}">All feeds</a></li>
                        <li><a href="{{ route('feed.latest') }}">All items</a></li>
                        <li><a href="{{ route('feed.today') }}">Todays items</a></li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @guest
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="{{ route('item.read') }}">Read items</a></li>
                                    <li><a href="{{ route('feed.saved-items') }}">Saved items</a></li>
                                    <li><a href="{{ route('feed.subscriptions') }}">Subscriptions</a></li>
                                    <li><a href="{{ route('feed.subscriptions.latest') }}">Latest subscription items</a></li>
                                    <li><a href="{{ route('group.index') }}">Groups</a></li>
                                    <li><a href="{{ route('feed.favourite-feeds') }}">Favourite feeds</a></li>
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <div class="row">
            <div style="width: 250px; position: fixed; top: 0; left: 0; bottom: 0; padding-top: 73px; background-color: #fff; border-right: 1px solid #d3e0e9; overflow-y: scroll">
                <ul class="nav">
                    <li><a href="{{ route('item.read') }}">Read items</a></li>
                    <li><a href="{{ route('feed.saved-items') }}">Saved items</a></li>
                    <li><a href="{{ route('feed.subscriptions') }}">Subscriptions</a></li>
                    <li><a href="{{ route('feed.subscriptions.latest') }}">Latest subscription items</a></li>
                    <li>
                        <a class="inline-block-important" role="button" data-toggle="collapse" href="#collapse-groups" aria-expanded="false" aria-controls="collapse-groups">
                          <span class="glyphicon glyphicon-collapse-down"></span>
                        </a>
                        <a class="inline-block-important" href="{{ route('group.index') }}">Groups</a>

                        <div class="collapse" id="collapse-groups">
                            @php
                            $groups = auth()->user()->groups;
                            @endphp
                            <ul class="nav" style="background-color: #f0f0f0">
                                @foreach ($groups as $group)
                                    <li><a href="{{ route('group.show', $group) }}">{{ $group->name }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                    <li><a href="{{ route('feed.favourite-feeds') }}">Favourite feeds</a></li>
                </ul>
            </div>
            <div style="margin-left: 250px;">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
