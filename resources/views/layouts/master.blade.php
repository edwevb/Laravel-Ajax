<!DOCTYPE html>
<html lang="en">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>@yield('title')</title>
	<!-- Bootstrap CSS -->
	<link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
	@include('layouts.navigation')
	@yield('container')
	<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
	<script src="{{ asset('js/app.js') }}" defer></script>
	@stack('custom-scripts')
	@yield('ajax')
</body>
</html>

