<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>Inventory App</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <noscript>
        <p>{{ env('APP_NAME') }} does not work with JavaScript disabled.</p>
        <p>Please enable JavaScript to use the app.</p>
    </noscript>

    <div id="app">
        </app>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>