<!DOCTYPE html>
<html lang="en-gb">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>TrashWEB</title>
        {{--<link rel="stylesheet" href="{{ asset("assets/css/libraries/bootstrap.min.css") }}">--}}
        <link rel="stylesheet" href="{{ asset("assets/css/libraries/egn-progressbar.css") }}">
        <link rel="stylesheet" href="{{ asset("assets/css/libraries/bootstrap-material-design.min.css") }}">
        <link rel="stylesheet" href="{{ asset("assets/css/app.css") }}">
    </head>

    <body>

        <div class="container-fluid h-100">
            @include("web._includes.navbar")
            @include("web._includes.progress")

            @yield("content")
        </div>

        <script src="{{ asset("assets/js/libraries/jquery-3.3.1.min.js") }}"></script>
        <script src="{{ asset("assets/js/libraries/axios.min.js") }}"></script>
        <script src="{{ asset("assets/js/libraries/moment.min.js") }}"></script>
        <script src="{{ asset("assets/js/libraries/chart.bundle.min.js") }}"></script>
        <script src="{{ asset("assets/js/libraries/bootstrap.bundle.min.js") }}"></script>
        <script src="{{ asset("assets/js/app.js") }}"></script>
        @yield("scripts")
    </body>
</html>
