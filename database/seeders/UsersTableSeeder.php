<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = [
            [
                'username' => 'superadmin', 
                'first_name' => 'Mark Romel',
                'middle_name' => 'F.',
                'last_name' => 'Feguro',
                'gender_id' => 1,
                'email' => 'superadmin@gmail.com',
                'contact_no' => '09672812221',
                'role_id' => 1,
                'password' => Hash::make('superadmin123'),
            ],

        ];

        DB::table('users')->insert($user);
    }
}