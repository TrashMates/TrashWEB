<!doctype html>
<html lang="en">
<head>
    <title>{{ $title }}</title>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- TrashMates CSS -->
    <link rel="stylesheet" href="{{ asset("css/animate.css") }}">
    <link rel="stylesheet" href="{{ asset("css/app.css") }}">
</head>
<body>
    @yield("content")
</body>
</html>