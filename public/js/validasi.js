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