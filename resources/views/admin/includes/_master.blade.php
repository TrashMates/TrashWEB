<!doctype html>
<html lang="en">
<head>
    <title>{{ $title }}</title>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="description" content="TrashMates - Admin Dashboard">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- TrashMates CSS -->
    <link rel="stylesheet" href="{{ asset("css/app.css") }}">
</head>
<body>
    @include("admin.includes.header")
    @include("admin.includes.navbar")

    <div class="container">
        @yield("content")
    </div>

    <script defer src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/all.js" integrity="sha384-xymdQtn1n3lH2wcu0qhcdaOpQwyoarkgLVxC/wZ5q7h9gHtxICrpcaSUfygqZGOe" crossorigin="anonymous"></script>
    <script defer src="{{ asset("js/app.js") }}"></script>

    @yield("scripts")
</body>
</html>