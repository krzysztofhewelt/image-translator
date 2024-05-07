<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#006abc">

        <title>Image translator</title>

        @vite('resources/js/app.js')
    </head>
    <body>
        <div id="app"></div>
        <noscript>Your browser does not support JavaScript!</noscript>
    </body>
</html>
