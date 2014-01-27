<!DOCTYPE html>
<html>
	<head>
		<title>Validasi Sederhana</title>
		{{-- Tampilan CSS Twitter Bootstrap --}}
		{{ HTML::style('css/bootstrap.min.css') }}
		<style type="text/css"> body { width:500px;margin:100px auto } </style>
	</head>
	<body>
		<h2 class="text-center">Demo Validasi AJAX</h2>
		<hr/>
		{{ Form::open(array('class'=>'form-horizontal')) }}
			{{-- Fokuskan perhatian pada id "form-username" dan "error-username" --}}
			<div class="form-group" id="form-username">
				{{ Form::label('username', 'Username', array('class'=>'col-sm-2 control-label')) }}
				<div class="col-sm-10">
					{{ Form::text('username', null, array('id'=>'username', 'class'=>'form-control')) }}
					<center><span class="help-block" id="error-username"></span></center>
				</div>

			</div>
			{{-- Fokuskan perhatian pada id "form-password" dan "error-password" --}}
			<div class="form-group" id="form-password">
				{{ Form::label('password', 'Password', array('class'=>'col-sm-2 control-label')) }}
				<div class="col-sm-10">
					{{ Form::password('password', array('id'=>'password', 'class'=>'form-control')) }}
					<center><span class="help-block" id="error-password"></span></center>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<a onclick="login()" class="btn btn-primary">Login</a>
				</div>
			</div>
		{{ Form::close() }}
		<hr/>
	{{-- Buat variabel berisi route tujuan --}}
	<script type="text/javascript">
		var url_home  = '{{ route('beranda') }}';
		var url_login = '{{ route('login') }}';
	</script>
	{{-- Panggil JQuery serta Validasi.js --}}
	{{ HTML::script('js/jquery-2.0.3.js') }}
	{{ HTML::script('js/validasi.js') }}	
	</body>
</html>