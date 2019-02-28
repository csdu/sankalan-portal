<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title'){{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    @routes
    <style>
        body {
            background-color: #f1f5f8;
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
<body class="font-sans bg-grey-lighter text-black">
    <div id="app" class="min-h-screen flex flex-col">
        @include('partials.navigation')
        <main class="flex flex-col flex-1">
            @yield('content')
        </main>
        @include('partials.footer')
        @include('flash::message')
    </div>
    <script src="{{ mix('js/app.js') }}" defer></script>
</body>
</html>
