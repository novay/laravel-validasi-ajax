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