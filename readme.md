# Validasi dengan jQuery / AJAX di Laravel

###Demo
![demo](https://raw.github.com/novay/novay-gallery/master/My%20Screenshot/Demo/laravel-validasi-ajax.gif)

###Fitur
- Validasi dengan AJAX
- Simple TB Layouts
- Nothing else XD

###Instalasi
 - Download zip lalu extract
 - `composer install`
 - `php artisan serv`
 - Kunjungi `localhost:8000` melalui browser

##Tutorial
- ###Bahan Tempur
  - Download [Laravel](https://github.com/laravel/laravel/archive/master.zip) lalu extract
  - Buka **terminal**, tuju ke direktori proyek,
  - `composer install`
  - Download [Twitter Bootstrap](http://getbootstrap.com/) (kita pake cara manual), lalu extract kedalam proyek laravel,
  - Dalam hal ini letakkan dalam `nama-proyek/public/*`
  - Sama halnya dengan [jQuery](jhttp://jquery.com), dan letakkan dalam `nama-proyek/public/js/*`.
  - Ditempat yang sama dengan jQuery, buat file baru dengan nama (katakan) `validasi.js`

- ###Konfigurasi Database
  - Buka `nama-proyek/app/config/database.php`
  - Set `'default' => 'mysql',` menjadi `'default' => 'sqlite',`.
  - Biar mudah, saya menggunakan koneksi `sqlite` sebagai drivernya.

- ###Migrations
  - `php artisan migrate:make buat_tabel_users`
  - Tuju ke `nama-proyek/app/database/migrations/*`
  - Buka file yang baru saja Anda buat, selipkan syntax-syntax berikut :

  - Untuk `public function up()`
		```
		Schema::create('users', function($t) {
			$t->increments('id');
			$t->string('username');
			$t->string('password');
		});
		```
  - Untuk `public function down()`
		```
		Schema::drop('users');
		```

  - eksekusi `php artisan migrate`
  - dan sekarang kita memiliki database dengan isi nama tabel `users`

- ###Seeds
  - Tuju ke direktori `nama-proyek/app/database/seeds/*`
  - Buat file baru dengan nama `TabelUserSeeder.php` dengan isi sebagai berikut :
	```
	<?php

	class TabelUserSeeder extends Seeder {

		public function run() {
			
			DB::table('users')->insert(array(
				'username' => 'admin', 'password' => Hash::make('admins')
			));
		}

	}
	```
  - terakhir, dalam direktori yg sama, buka file `DatabaseSeeder.php`
  - selipkan kode `$this->call('TabelUserSeeder');` dibawah kode `Eloquent::unguard();`
  - kembali ke **terminal**, eksekusi `php artisan db:seed`

- ###Models
  - Berhubung nama tabel yg dibuat bernama `users`, jadi perubahan pada models tidak dibutuhkan. Karena secara default, laravel akan mengakses tabel users sebagai tabel bawaan pengguna. Anda bisa memastikannya di `nama-proyek/app/models/User.php` pada syntax `protected $table = 'users';`, dimana `users` disini bertindak sebagai tabel tujuan.

- ###Controllers
  - Untuk penerapan kali ini, saya akan membuat sebuah Controller di `nama-proyek/app/controllers/*` dengan nama `ValidasiController.php` dengan isi sebagai berikut :
  ```
	<?php

	class ValidasiController extends BaseController {

		/**
		 * Pemberian fungsi konstruksi untuk mem-filter aksi
		 */
		public function __construct() {
			# Koleksi filter
			$this->beforeFilter('ajax',  array('on' => array('postLogin', 'getLogout')));
		}

		/**
		 * Halaman beranda
		 */
		public function getIndex() {
			# Jika user bertindak sebagai tamu, rujuk ke login
			if (Auth::guest()) return Redirect::to('login');
			# jika sudah login tampilkan beranda
			return View::make('beranda');
		}

		/**
		 * Halaman login
		 */
		public function getLogin() {
			# tampilkan halaman login
			return View::make('login');
		}

		/**
		 * Verifikasi akun
		 */
		public function postLogin() {
			# validasi
			$input = Input::all();
			$rules = array('username'=>'required|min:5','password'=>'required|min:6');
			$v = Validator::make($input, $rules);
			# jika validasi gagal
			if ($v->fails()) {
				# koleksi pesan error tiap variabel
				$username = $v->messages()->first('username') ?: '';
				$password = $v->messages()->first('password') ?: '';
				$status = '';
				# kirim json dengan status '' untuk kegagalan XD
				return Response::json(compact('username', 'password', 'status'));
			}
			# untuk validasi sukses, koleksi inputan form
			$cocok = array(
				'username' => Input::get('username'), 
				'password' => Input::get('password')
				);
			# proses pencocokan cocok
			if (Auth::attempt($cocok)) {
				# kirim status sukses
				$status = 'sukses';
				return Response::json(compact('status'));
			# bila tidak cocok
			} else {
				# kirim status error
				$status = 'gagal';
				return Response::json(compact('status'));
			}
		}

		/**
		 * Logout akun
		 */
		public function getLogout() {
			# logout admin
			Auth::logout();
		}

	}
	```

- ###Routes
  - Sekarang edit file `nama-proyek/app/routes.php`
  - Gantikan isi yang ada dengan syntax-syntax berikut :
  	```
	<?php

	Route::filter('ajax', function() {
		if (!Request::ajax()) return App::abort(404);
	});	

	Route::get('/', array('as' => 'beranda', 'before'=>'auth', 'uses' => 'ValidasiController@getIndex'));
	Route::get('login', array('as' => 'login', 'uses' => 'ValidasiController@getLogin'));
	Route::post('login', array('uses' => 'ValidasiController@postLogin'));
	Route::get('logout', array('as' => 'logout', 'before'=>'auth', 'uses' => 'ValidasiController@getLogout'));
	```

- ###Views
  - Sekarang fokus dalam pembuatan tampilan di `nama-proyek/app/views/*`
  - Pertama buat file `login.blade.php`, isinya sebagai berikut :
  	```
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
	```

  - Sekarang buat tampilan beranda, buat lagi file baru ditempat yang sama dengan nama `beranda.blade.php`
	```
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
	```

  - Terakhir kita buat setiap fungsi yang digunakan dalam file `validasi.js` kita, 
  - buka kembali `nama-proyek/public/js/validasi.js` lalu isi syntax berikut :
	```
	// untuk mengenali fungsi "Enter" setelah username danpassword di inputkan, gunakan fungsi berikut :
	$('input').keypress(function(k) { if (k.which == 13) login(); });

	// logika fungsi Login
	function login() {
		// koleksi variabel dari form
		var username = $('#username').val();
		var password = $('#password').val();
		// eksekusi
		$.post(url_login, { username:username, password:password }, function(r) {
			// jika status yg diterima ''
			if (r.status == '') {
				// jika username tidak sama dengan ''
				if (r.username != '') {
					// ubah class berdasarkan id  masing-masing
					$('#form-username').removeClass('has-success').addClass('has-error');
					$('#error-username').text(r.username);
					$('#password').val('');
				// sedang, bila username = ''
				} else {
					// tambah class jadi has-success berdasarkan id
					$('#form-username').removeClass('has-error').addClass('has-success');
					$('#error-username').text('');
				};
				// untuk password, penjelasan sama
				if (r.password != '') {
					$('#form-password').removeClass('has-success').addClass('has-error');
					$('#error-password').text(r.password);
					$('#password').val('');
				} else {
					$('#form-password').addClass('has-error');
					$('#error-password').text('');
				};
			// sedang, jika status tidak kosong
			} else {
				// untuk nilai value sukses
				if (r.status == 'sukses') {
					// rujuk ke url_home
					$(location).prop('href', url_home);
				// selain sukses, eksekusi ini
				} else {
					// hapus semua class dan pesan has-error kmudian fokuskan kursor ke username
					$('.form-group').removeClass('has-success has-error').find('input, .help-block').val('').text('Username dan password ngawur!');
					$('#username').focus();
				};
			};
		}, "json");
	}

	// fungsi logout
	function logout() {
		$.get(url_logout, function() {
			$(location).prop('href', url_login);
		});
	}
	```

 - ###Selesai		
   - Kembali ke **Terminal** lalu jalankan perintah `php artisan serv`.
    - Buka Browser, dan akses `localhost:8000`.

##Credit
- Laravel
- Twitter Bootstrap
- jQuery
- Heru

####**Just Be Initiative**. Regard [Novay](http://novay.web.id).