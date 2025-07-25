<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title'){{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">

    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('favicon/apple-icon-60x60.png') }}" />
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('favicon/apple-icon-72x72.png') }}" />
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicon/apple-icon-120x120.png') }}" />
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('favicon/apple-icon-144x144.png') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-icon-180x180.png') }}" />
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicon/android-icon-192x192.png') }}" />
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon/favicon.ico') }}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}" />
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon/favicon-96x96.png') }}" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @routes
    <style>
        body {
            background-color: var(--color-grey-lighter);
            background-image: url("{{ asset('/svg/circuit-bg.svg') }}");
        }
        body::before {
            content: '';
            background-image: url("{{ asset('/svg/logo.svg') }}");
            width: calc(30vh + 10vw);
            height: calc(30vh + 10vw);
            background-size: calc(30vh + 10vw);
            position: fixed;
            left: 0;
            top: 50%;
            z-index: -10;
            opacity: .3;
            transform: translate(-50%, -50%);
        }
        body::after {
            content: '';
            background-image: url("{{ asset('/svg/logo.svg') }}");
            width: calc(30vw + 30vh);
            height: calc(30vw + 30vh);
            background-size: calc(30vw + 30vh);
            position: fixed;
            right: 0;
            bottom: 0;
            z-index: -10;
            opacity: .2;
            transform: translate(25%, 25%);
        }
    </style>
</head>
<body class="font-sans bg-slate-100 text-black">
    <div id="app" class="min-h-screen flex flex-col">
        @include('partials.navigation')
        <main class="flex flex-col flex-1">
            @yield('content')
        </main>
        @include('partials.footer')
        @include('flash::message')
    </div>
</body>
</html>
