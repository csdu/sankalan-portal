<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @stack('meta')

        <title> @yield('title'){{ config('app.name') }} </title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        @stack('stylesheets')
    </head>
    <body class="font-sans text-black leading-tight bg-grey-lighter">
        <div id="app" class="min-h-screen flex flex-col">
            @yield('body')
        </div>        
        <script src="{{ mix('js/app.js') }}"></script>
        @stack('scripts')
    </body>
</html>
