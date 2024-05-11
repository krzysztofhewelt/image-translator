<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#006abc">
        <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

        <title>Image translator</title>

        @vite('resources/js/app.ts')
    </head>
    <body>
        <div id="app"></div>
        <noscript>Your browser does not support JavaScript!</noscript>
    </body>
</html>
