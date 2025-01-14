<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $setting = [
            [
                "user_id" => 1, //superadmin
            ],
        ];

        DB::table("settings")->insert($setting);
    }
}
