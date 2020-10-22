<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
</head>
<body class="@auth @if(Auth::user()->dark_mode) dark-mode @endif @endauth">
    <div id="app">
        <nav class="navbar navbar-expand-md @auth @if(Auth::user()->dark_mode) dark-mode-nav nav-dark-border @else bg-white navbar-light @endif @endauth @guest bg-white navbar-light @endguest shadow-sm">
            <div class="container">
                <a class="navbar-brand @auth @if(Auth::user()->dark_mode) dark-mode-nav @else bg-white navbar-light @endif @endauth @guest bg-white navbar-light @endguest" href="{{ route('dashboard') }}">
                    {{ config('app.name', 'Tavern Tracker') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        @auth
                        <div class="dropdown">
                            <button class="btn @auth @if(Auth::user()->dark_mode) dark-mode-nav @else btn-light @endif @endauth dropdown-toggle" type="button" id="guildwarmenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Guild war
                            </button>
                            <div class="dropdown-menu @if(Auth::user()->dark_mode) dark-mode-nav @endif" aria-labelledby="guildwarmenu">
                                <a  href="{{route('guildwar-statistic')}}" class="dropdown-item @if(Auth::user()->dark_mode) dark-mode-nav @endif" type="button">Statistics</a>
                                <a  href="#" class="dropdown-item @auth @if(Auth::user()->dark_mode) dark-mode-nav hover @endif @endauth" type="button">Management</a>
                            </div>
                        </div>
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto @auth @if(Auth::user()->dark_mode) dark-mode-nav @endif @endauth">
                        <!-- Authentication Links -->
                        @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                        @endif
                        @else
                        <li class="nav-item dropdown @auth @if(Auth::user()->dark_mode) dark-mode-nav @endif @endauth">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle @if(Auth::user()->dark_mode) dark-mode-nav @endif" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right @if(Auth::user()->dark_mode) dark-mode-nav @endif" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item @if(Auth::user()->dark_mode) dark-mode-nav @endif" href="{{ route('settings') }}">
                                    {{ __('Settings') }}
                                </a>
                                <a class="dropdown-item @if(Auth::user()->dark_mode) dark-mode-nav @endif" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>
</div>
</body>
</html>
