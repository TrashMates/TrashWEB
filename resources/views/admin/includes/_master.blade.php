<!doctype html>
<html lang="en">
    <head>
        <title>{{ $title }}</title>

        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="description" content="TrashMates - Admin Dashboard">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Favicon -->
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset("mobile/apple-touch-icon.png") }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset("mobile/favicon-32x32.png") }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset("mobile/favicon-16x16.png") }}">
        <link rel="manifest" href="{{ asset("mobile/site.webmanifest") }}">
        <link rel="mask-icon" href="{{ asset("mobile/safari-pinned-tab.svg") }}" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#B63636">
        <meta name="theme-color" content="#B63636">

        <!-- TrashMates CSS -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
        <link rel="stylesheet" href="{{ asset("css/loading.css") }}">
        <link rel="stylesheet" href="{{ asset("css/app.css") }}">
    </head>
    <body>
        @include("admin.includes.header")
        @include("admin.includes.navbar")

        <div class="container">
            @yield("content")
        </div>

        <script defer src="{{ asset("js/libs/jquery-3.3.1.min.js") }}"></script>
        <script defer src="{{ asset("js/libs/Chart.bundle.min.js") }}"></script>
        <script defer src="{{ asset("js/libs/font-awesome.min.js") }}"></script>
        <script defer src="{{ asset("js/app.js") }}"></script>
        <script defer src="{{ asset("js/loading.js") }}"></script>

        @yield("scripts")
    </body>
</html>