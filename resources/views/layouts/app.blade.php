<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body class="font-sans text-black">
    <div id="app" class="min-h-screen flex flex-col">
        <nav class="bg-blue text-white">
            <div class="container mx-auto flex flex-col md:flex-row">
                <a class="font-bold text-lg py-2 px-3" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>

                <!-- Left Side Of Navbar -->
                <ul class="list-reset flex items-center mx-auto">

                </ul>

                <ul class="list-reset flex">
                    @guest
                        <li class="nav-item">
                            <a class="h-full px-2 py-1 uppercase tracking-wide text-xs font-semibold inline-flex items-center" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="h-full px-2 py-1 uppercase tracking-wide text-xs font-semibold inline-flex items-center" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="group relative inline-block">
                            <a id="navbarDropdown" class="h-full px-2 py-1 uppercase tracking-wide text-xs font-semibold inline-flex items-center" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} ({{ Auth::user()->uid }})
                                <svg viewBox="0 0 15 10" style="height: .5em" class="ml-1 text-white">
                                    <polygon points="0,0 15,0 7.5,10" fill="currentColor"></polygon>
                                </svg>
                            </a>

                            <div class="group-hover:block hidden absolute z-10 pin-r bg-white text-black py-2 text-right shadow rounded border" aria-labelledby="navbarDropdown">
                                <a class="inline-block py-1 px-4 hover:bg-blue hover:text-white" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </nav>

        <main class="py-4 flex-1">
            @yield('content')
        </main>
    </div>
    <script src="{{ mix('js/app.js') }}" defer></script>
</body>
</html>
