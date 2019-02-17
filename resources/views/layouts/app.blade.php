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
    <style>
        body {
            background-color: #f1f5f8;
            background-image: url("{{ asset('/svg/circuit-bg.svg') }}");
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
