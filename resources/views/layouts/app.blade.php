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
    @routes
</head>
<body class="font-sans bg-grey-lighter text-black">
    <div id="app" class="min-h-screen flex flex-col">
        <nav class="bg-blue text-white">
            <div class="container mx-auto flex flex-col md:flex-row">
                <a class="font-bold text-lg py-2 px-3" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>

                <!-- Left Side Of Navbar -->
                <ul class="list-reset flex items-center mx-auto">
                    @auth
                    <li>
                        <a class="h-full px-2 py-1 uppercase tracking-wide text-xs font-semibold inline-flex items-center" href="{{ route('teams') }}">Teams</a>
                    </li>
                    @endauth

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

        <main class="flex flex-col flex-1">
            @yield('content')
        </main>
        @include('flash::message')
        <footer class="py-3 {{ Request::is('/') ? 'bg-blue' : '' }}">
            <p class="{{ Request::is('/') ? 'text-white' : 'text-grey-dark' }} text-center">
                <span class="font-bold">Sankalan &copy; 2019</span>
                &mdash;
                <span class="inline-flex items-center">
                    <span>made with</span>
                    <svg class="h-4 text-red mx-1" version="1.0" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                        <path fill="currentColor" d="M462.3 62.6C407.5 15.9 326 24.3 275.7 76.2L256 96.5l-19.7-20.3C186.1 24.3 104.5 15.9 49.7 62.6c-62.8 53.6-66.1 149.8-9.9 207.9l193.5 199.8c12.5 12.9 32.8 12.9 45.3 0l193.5-199.8c56.3-58.1 53-154.3-9.8-207.9z"></path>
                    </svg>
                    <span>
                        by <a class="font-bold hover:underline" href="#">Ruman Saleem</a>
                    </span>
                </span>
            </p>
        </footer>
    </div>
    <script src="{{ mix('js/app.js') }}" defer></script>
</body>
</html>
