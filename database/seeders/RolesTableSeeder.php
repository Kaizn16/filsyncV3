<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['role_type' => 'superadmin'],
            ['role_type' => 'admin'],
            ['role_type' => 'teacher'],
            ['role_type' => 'vpaa'],
            ['role_type' => 'registrar'],
            ['role_type' => 'hr'],
        ];

        DB::table('roles')->insert($roles);
    }
}
