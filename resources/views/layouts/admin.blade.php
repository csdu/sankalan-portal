<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('stylesheets')
    @routes
</head>
<body class="font-sans bg-slate-100 text-black overflow-x-auto" style="min-width: 1024px;">
    <div id="app" class="min-h-screen flex flex-col px-4 md:px-10">
        <main class="flex-1 flex flex-col my-4">
            <div class="flex items-baseline justify-between mb-6 border-b border-slate-200">
                <h1 class="mb-3 text-2xl">Sankalan<small class="ml-1 text-sm uppercase text-blue-500">Admin</small> </h1>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="btn is-red font-normal">Logout</button>
                </form>
            </div>
            <div class="flex-1 flex">
                <aside class="w-48 pt-4 mr-4">
                    <ul class="nav-bar list-reset">
                        <li class="nav-bar-item">
                            <a href="{{ route('admin.dashboard') }}"
                                class="inline-block w-full hover:text-blue-600 px-4 py-2 rounded {{ Request::segment(2) === 'dashboard' ? 'text-blue-700 font-bold' : ' text-slate-700' }}">
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-bar-item">
                            <a href="{{ route('admin.events.index') }}"
                                class="inline-block w-full hover:text-blue-600 px-4 py-2 rounded {{ Request::segment(2) === 'events' ? 'text-blue-700 font-bold' : ' text-slate-700' }}">
                                Events
                            </a>
                        </li>
                        <li class="nav-bar-item">
                            <a href="{{ route('admin.events.teams.index') }}"
                                class="inline-block w-full hover:text-blue-600 px-4 py-2 rounded {{ Request::segment(2) === 'events_teams' ? 'text-blue-700 font-bold' : ' text-slate-700' }}">
                                Participations
                            </a>
                        </li>
                        <li class="nav-bar-item">
                            <a href="{{ route('admin.quizzes.index') }}"
                                class="inline-block w-full hover:text-blue-600 px-4 py-2 rounded {{ Request::segment(2) === 'quizzes' ? 'text-blue-700 font-bold' : ' text-slate-700' }}">
                                Quizzes
                            </a>
                        </li>
                        <li class="nav-bar-item">
                            <a href="{{ route('admin.quizzes.teams.index') }}"
                                class="inline-block w-full hover:text-blue-600 px-4 py-2 rounded {{ Request::segment(2) === 'quizzes_teams' ? 'text-blue-700 font-bold' : ' text-slate-700' }}">
                                Quiz Participations
                            </a>
                        </li>
                        <li class="nav-bar-item">
                            <a href="{{ route('admin.teams.index') }}"
                            class="inline-block w-full hover:text-blue-600 px-4 py-2 rounded {{ Request::segment(2) === 'teams' ? 'text-blue-700 font-bold' : ' text-slate-700' }}">
                                Teams
                            </a>
                        </li>
                        <li class="nav-bar-item">
                            <a href="{{ route('admin.users.index') }}"
                            class="inline-block w-full hover:text-blue-600 px-4 py-2 rounded {{ Request::segment(2) === 'users' ? 'text-blue-700 font-bold' : ' text-slate-700' }}">
                                Users
                            </a>
                        </li>
                    </ul>
                </aside>
                <div class="flex-1">
                    @yield('content')
                </div>
            </div>
        </main>
        @include('flash::message')
        <footer class="py-3 text-slate-600">
            <p class="mb-1 text-center">
                Copyright &copy; {{ config('app.sankalan_start_time')->format('Y') }}. Department of Computer Science Society.
            </p>
            <p class="flex items-center justify-center">
                <span>Made with</span>
                <svg class="h-4 text-red-500 mx-1" viewBox="0 0 20 20">
                    <path fill="currentColor"
                        d="M10 3.22l-.61-.6a5.5 5.5 0 0 0-7.78 7.77L10 18.78l8.39-8.4a5.5 5.5 0 0 0-7.78-7.77l-.61.61z" />
                </svg>
                <span>
                    by <a class="font-bold hover:underline" href="https://rumansaleem.github.io/">Ruman Saleem</a>
                    and <a class="font-bold hover:underline" href="https://github.com/yuvrajsab">Yuvraj Sablania</a>
                </span>
            </p>
        </footer>
    </div>
    @stack('scripts')
</body>
</html>
