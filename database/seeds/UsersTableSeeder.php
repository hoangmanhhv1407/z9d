<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();
        App\User::create([
        	'name' => 'Admin',
        	'email' =>'admin@gmail.com',
        	'password' => bcrypt('12345678')
        ]);
    }
}
