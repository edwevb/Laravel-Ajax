<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('users')->insert([
    		'name'       => 'Admin',
    		'email'      => 'admin@gunadarma.com',
    		'password'   => bcrypt('admin123'),
    		'created_at' => now()
    	]);
    }
  }
