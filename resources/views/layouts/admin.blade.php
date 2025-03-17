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

    @stack('stylesheets')

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    @routes
</head>
<body class="font-sans bg-grey-lighter text-black overflow-x-auto" style="min-width: 1024px;">
    <div id="app" class="min-h-screen flex flex-col px-4 md:px-10">
        <main class="flex-1 flex flex-col my-4">
            <div class="flex items-baseline justify-between mb-6 border-b">
                <h1 class="mb-3 text-2xl">Sankalan<small class="ml-1 text-sm uppercase text-blue">Admin</small> </h1>
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
                                class="inline-block w-full text-grey-darker hover:text-blue px-4 py-2 rounded{{ Request::segment(2) === 'dashboard' ? ' text-blue font-semibold' : ' ' }}">
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-bar-item">
                            <a href="{{ route('admin.events.index') }}"
                                class="inline-block w-full text-grey-darker hover:text-blue px-4 py-2 rounded{{ Request::segment(2) === 'events' ? ' text-blue font-semibold' : ' ' }}">
                                Events
                            </a>
                        </li>
                        <li class="nav-bar-item">
                            <a href="{{ route('admin.events.teams.index') }}"
                                class="inline-block w-full text-grey-darker hover:text-blue px-4 py-2 rounded{{ Request::segment(2) === 'events_teams' ? ' text-blue font-semibold' : ' ' }}">
                                Participations
                            </a>
                        </li>
                        <li class="nav-bar-item">
                            <a href="{{ route('admin.quizzes.index') }}"
                                class="inline-block w-full text-grey-darker hover:text-blue px-4 py-2 rounded{{ Request::segment(2) === 'quizzes' ? ' text-blue font-semibold' : ' ' }}">
                                Quizzes
                            </a>
                        </li>
                        <li class="nav-bar-item">
                            <a href="{{ route('admin.quizzes.teams.index') }}"
                                class="inline-block w-full text-grey-darker hover:text-blue px-4 py-2 rounded{{ Request::segment(2) === 'quizzes_teams' ? ' text-blue font-semibold' : ' ' }}">
                                Quiz Participations
                            </a>
                        </li>
                        <li class="nav-bar-item">
                            <a href="{{ route('admin.teams.index') }}"
                            class="inline-block w-full text-grey-darker hover:text-blue px-4 py-2 rounded{{ Request::segment(2) === 'teams' ? ' text-blue font-semibold' : ' ' }}">
                                Teams
                            </a>
                        </li>
                        <li class="nav-bar-item">
                            <a href="{{ route('admin.users.index') }}"
                            class="inline-block w-full text-grey-darker hover:text-blue px-4 py-2 rounded{{ Request::segment(2) === 'users' ? ' text-blue font-semibold' : ' ' }}">
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
        <footer class="py-3 text-grey-dark">
            <p class="mb-1 text-center">
                Copyright &copy; {{ config('app.sankalan_start_time')->format('Y') }}. Department of Computer Science Society.
            </p>
            <p class="flex items-center justify-center">
                <span>Made with</span>
                <svg class="h-4 text-red mx-1" viewBox="0 0 20 20">
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
    <script src="{{ mix('js/app.js') }}" defer></script>
    @stack('scripts')
</body>
</html>
