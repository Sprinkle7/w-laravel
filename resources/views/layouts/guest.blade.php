<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <!-- Fonts -->
    </head>
    <body class="bg-gray-100 flex items-center justify-center min-h-screen" style="background:url({{ asset('lines.png') }}); background-size:cover;">
        {{ $slot }}
    </body>
</html>
