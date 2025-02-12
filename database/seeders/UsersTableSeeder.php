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
        $users = [
            [
                'username' => 'superadmin', 
                'first_name' => 'Super',
                'middle_name' => '',
                'last_name' => 'Admin',
                'gender_id' => 1,
                'email' => 'superadmin@gmail.com',
                'contact_no' => '09672812221',
                'role_id' => 1,
                'position' => null,
                'department_id' => null,
                'password' => Hash::make('superadmin123'),
            ],
            [
                'username' => 'FCU-Jonah', 
                'first_name' => 'Jonah',
                'middle_name' => 'Palomar',
                'last_name' => 'Gafate',
                'gender_id' => 2,
                'email' => 'jonahpalomar@gmail.com',
                'contact_no' => '09123456789',
                'role_id' => 2,
                'position' => 'DEAN',
                'department_id' => 5,
                'password' => Hash::make('12345678'),
            ],
            [
                'username' => 'FCU-Mark', 
                'first_name' => 'Mark Romel',
                'middle_name' => 'Faderes',
                'last_name' => 'Feguro',
                'gender_id' => 1,
                'email' => 'markromelfeguro1@gmail.com',
                'contact_no' => '09388444436',
                'role_id' => 3,
                'position' => 'TEACHER',
                'department_id' => 5,
                'password' => Hash::make('12345678'),
            ]

        ];

        foreach ($users as $user) {
            $userId = DB::table('users')->insertGetId($user);

            DB::table('settings')->insert([
                'user_id' => $userId,
            ]);
        }
    }
}