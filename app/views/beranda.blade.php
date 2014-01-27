<!DOCTYPE html>
<html>
	<head>
		<title>Validasi Sederhana</title>
		{{-- Tampilan CSS Twitter Bootstrap --}}
		{{ HTML::style('css/bootstrap.min.css') }}
		<style type="text/css"> body { width:500px; margin:100px auto } </style>
	</head>
	<body class="text-center">
		<h2>Demo Validasi AJAX</h2>
		<hr/>
		<h3>Selamat Datang, {{ Auth::user()->username }}</h3>
		<p>Ini adalah halaman Beranda sederhana Anda.</p>
		<p>Tentunya dengan ini, diharapkan Anda dapat menerapkan AJAX dalam penggunaan Laravel dan tidak hanya untuk validasi.</p>
		<hr/>
		{{-- Perhatikan di sini kita menggunakan onclick bukan href --}}
		<a onclick="logout()" class="btn btn-danger">Logout</a>
	{{-- Buat variabel berisi route tujuan --}}
	<script type="text/javascript">
		var url_login = '{{ route('login') }}';
		var url_logout = '{{ route('logout') }}';
	</script>
	{{-- Panggil JQuery serta Validasi.js --}}
	{{ HTML::script('js/jquery-2.0.3.js') }}
	{{ HTML::script('js/validasi.js') }}
	</body>
</html>