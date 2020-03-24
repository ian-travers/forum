<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ mix('css/app.css', 'build') }}" rel="stylesheet">
</head>
<body>
<div id="app">

    @include('layouts._nav')

    <main class="py-3">

        @yield('content')
    </main>

    <flash message="{{ session('flash') }}"></flash>

    @if(session('verified'))
        <flash message="Your email address is verified."></flash>
    @endif
</div>
<!-- Scripts -->
<script>
    window.App = {!! json_encode([
        'user' => auth()->user(),
        'signedIn' => auth()->check()
    ]) !!}
</script>
<script src="{{ mix('js/app.js', 'build') }}"></script>
</body>
</html>
