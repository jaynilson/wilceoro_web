<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
     DB::table('user')->insert([
    'name' => 'Joe',
    'last_name' => 'Smith',
    'password' => bcrypt('123'), // password
    'tel' => '', 
    'email' => 'jaynilson709@gmail.com',
    'user_name' => '',
    'address' => '',
    'sex' => 'male',
    'color' => '#ff0000',
    'date_of_birth' => date("Ymd"),
    'created_at'=>now(),
    'updated_at'=>now(),
    'id_rol' => 1
    ]);
  
    }
}
