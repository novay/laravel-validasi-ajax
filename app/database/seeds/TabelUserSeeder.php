<?php

class TabelUserSeeder extends Seeder {

	public function run() {
		
		DB::table('users')->insert(array(
			'username' => 'admin', 'password' => Hash::make('admins')
		));
	}

}