<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<!-- My CSS -->
	<link rel="stylesheet" href="{{ asset('assets/style.css') }}">
<!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<title>Gestionnaire dashboard</title>
</head>
<body>

    <!-- SIDEBAR -->
	@include('layouts.gestionnaire.inc.sidebar')
    <!-- SIDEBAR -->


	<!-- CONTENT -->
	<section id="content">
		<!-- NAVBAR -->
		@include('layouts.gestionnaire.inc.navbar')
		<!-- NAVBAR -->

		<!-- MAIN -->
		@yield('content')
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->
	<!-- In resources/views/layouts/admin/master.blade.php -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

	<script src="{{ asset('assets/script.js') }}"></script>
	<!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
</body>
</html>