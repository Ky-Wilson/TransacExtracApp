<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<!-- My CSS -->
	<link rel="stylesheet" href="{{ asset('assets/style.css') }}">

	<title>AdminHub</title>
</head>
<body>

    <!-- SIDEBAR -->
	@include('layouts.admin.inc.sidebar')
    <!-- SIDEBAR -->


	<!-- CONTENT -->
	<section id="content">
		<!-- NAVBAR -->
		@include('layouts.admin.inc.navbar')
		<!-- NAVBAR -->

		<!-- MAIN -->
		@yield('content')
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->
	

	<script src="{{ asset('assets/script.js') }}"></script>
</body>
</html>