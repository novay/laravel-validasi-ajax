<?php

Route::filter('ajax', function() {
	if (!Request::ajax()) return App::abort(404);
});	

Route::get('/', array('as' => 'beranda', 'before'=>'auth', 'uses' => 'ValidasiController@getIndex'));
Route::get('login', array('as' => 'login', 'uses' => 'ValidasiController@getLogin'));
Route::post('login', array('uses' => 'ValidasiController@postLogin'));
Route::get('logout', array('as' => 'logout', 'before'=>'auth', 'uses' => 'ValidasiController@getLogout'));
