<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insert admin data
        DB::table('admins')->insert([
            'name' => 'Superadmin',
            'email' => 'superadmin2023@gmail.com',
            'password' => Hash::make('password1234'),
            'role_id' => 1,
        ]);
    }
}
