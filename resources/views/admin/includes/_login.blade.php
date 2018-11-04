<!doctype html>
<html lang="en">
	<head>
		<title>{{ $title }}</title>

		<!-- Required meta tags -->
		<meta charset="utf-8">
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
		<link rel="stylesheet" href="{{ asset("css/animate.css") }}">
		<link rel="stylesheet" href="{{ asset("css/app.css") }}">
	</head>
	<body>
		@yield("content")
	</body>
</html>