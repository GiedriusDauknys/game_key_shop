<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

@include('partials.header')

<main>
    @yield('content')
</main>

@include('partials.footer')

<script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
