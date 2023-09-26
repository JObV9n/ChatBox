<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @extends('layouts.head')
    <title>{{ config('app.name', 'Laravel') }}</title>
</head>
<body>
<div id="app">
    @include('layouts.navbar')

    <main class="py-4">
        @yield('content')
    </main>
</div>
</body>
</html>
