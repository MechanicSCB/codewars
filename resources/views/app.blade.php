<!DOCTYPE html>
<html class="" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/my-highlight.css') }}">

    <!-- SCRIPTS -->
    <script src="{{ asset('js/toggleTheme.js') }}"></script>
    <!-- CODEMIRROR -->
    <script src="{{ asset('plugins/codemirror-5.59.4/lib/codemirror.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('plugins/codemirror-5.59.4/lib/codemirror.css') }}">
    <script src="{{ asset('plugins/codemirror-5.59.4/mode/javascript/javascript.js') }}"></script>
    <script src="{{ asset('plugins/codemirror-5.59.4/mode/php/php.js') }}"></script>
    <script src="{{ asset('plugins/codemirror-5.59.4/mode/htmlmixed/htmlmixed.js') }}"></script>

    <!-- SUMMERNOTE -->
    <script src="{{ asset('plugins/jquery-3.4.1.slim.min.js') }}"></script>
    <link href="{{ asset('plugins/summernote-0.8.18-dist/summernote-lite_orig.css') }}" rel="stylesheet">
    <script src="{{ asset('plugins/summernote-0.8.18-dist/summernote-lite.min.js') }}"></script>

    <!-- Scripts -->
    @routes
    @vite('resources/js/app.js')
    @inertiaHead
</head>
<body onload="setTheme();" class="pt-[65px] font-sans antialiased absolute w-full text-ui-text light h-full bg-ui-body">
@inertia
</body>
</html>
