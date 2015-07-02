<!DOCTYPE html>
<html lang="en">
<head>
    <title>Deluagara - @yield('title')</title>
    <meta charset="UTF-8">
    <meta name=description content="">
    <meta name=viewport content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ elixir('css/vendor.css') }}">
    <link rel="stylesheet" href="{{ elixir('css/app.css') }}">
    @yield('header')
</head>
<body>
    @include('layouts.navbar')

    @yield('map-front')

    <div class="container" id="deluagara">
        @yield('content')
    </div>

    <script src="{{ elixir('js/vendor.js') }}"></script>
    @yield('footer')
</body>
</html>