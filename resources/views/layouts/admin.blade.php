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
<body class="font-sans bg-grey-lighter text-black overflow-x-auto" style="min-width: 1024px;">
    <div id="app" class="min-h-screen flex flex-col px-4 md:px-10">
        <main class="flex-1 flex flex-col my-4">
            <div class="flex items-baseline justify-between mb-6 border-b">
                <h1 class="mb-3">Sankalan</h1>
                <a href="#logout" class="btn is-red">Logout</a>
            </div>
            <div class="flex-1 flex">
                <aside class="w-48 pt-4 mr-4">
                    <ul class="nav-bar list-reset">
                        <li class="nav-bar-item">
                            <a href="{{ route('events.index') }}" 
                            class="inline-block w-full text-grey-dark hover:text-black px-4 py-2 rounded">Events</a>
                        </li>
                        <li class="nav-bar-item">
                            <a href="{{ route('participations.index') }}" 
                            class="inline-block w-full text-grey-dark hover:text-black px-4 py-2 rounded">Participations</a></a>
                        </li>
                    </ul>
                </aside>
                <div class="flex-1">
                    @yield('content')
                </div>
            </div>
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
