<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5">
        <script>
            window.auth = {!! auth()->user() !!}
        </script>
        {{-- user-scalable=no --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="author" content="{{ config('app.author') }}">
        <meta name="description" content="{{ config('app.name', 'CleanLaravel') }}">
        <base href="{{ URL::to('/') }}">
        <link href="{{ asset('/img/icon.png') }}" rel="shortcut icon">
        <!-- Styles -->
        @yield("meta")

        <title>{{ config('app.name', 'CleanLaravel') }}</title>

        <!-- Styles -->
        @yield("styles")
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        <!-- Scripts -->
        @yield("top-scripts")
        <script src="{{ mix('js/app.js') }}"></script>
        <script src="{{ mix('js/main.js') }}"></script>
    </head>
    <body class="antialiased">
        <div id="fb-root"></div>

        <div id="page-container">
            @yield("navbar")
            @yield("content")
            @yield("footer")
        </div>

        {!! htmlScriptTagJsApi() !!}
        @yield("scripts")
        <!-- Bottom Styles -->
        @yield("bottom-styles")
    </body>
</html>
