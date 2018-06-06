<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="TrashMates, des streams calmes et variés le mercredi et vendredi de 20h à 22h.">
        <meta name="author" content="TrashMates">
        <title>TrashMates</title>

        <!-- Bootstrap core CSS -->
        <link href="{{ asset("css/bootstrap.css") }}" rel="stylesheet">
        <link href="{{ asset("css/loading.css") }}" rel="stylesheet">
        <link href="{{ asset("css/app.css") }}" rel="stylesheet">

        <!-- Favicon -->
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset("mobile/apple-touch-icon.png") }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset("mobile/favicon-32x32.png") }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset("mobile/favicon-16x16.png") }}">
        <link rel="manifest" href="{{ asset("mobile/site.webmanifest") }}">
        <link rel="mask-icon" href="{{ asset("mobile/safari-pinned-tab.svg") }}" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#B63636">
        <meta name="theme-color" content="#B63636">
    </head>

    <body>

        @include("trashmates.includes.navbar")
        <div class="progress" id="progessbar">
            <div class="indeterminate"></div>
        </div>
        <div class="container-fluid">
            @yield('content')
        </div>

        <script defer src="{{ asset("js/libs/jquery-3.3.1.min.js") }}"></script>
        <script defer src="{{ asset("js/loading.js") }}"></script>
        <script defer src="https://content.jwplatform.com/libraries/sQ0EAcjD.js"></script>
        <script defer src="{{ asset("js/stream.js") }}"></script>
    </body>
</html>
