<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class YearLevelTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $year_levels = [
            ['year_level' => '1st Year'],
            ['year_level' => '2nd Year'],
            ['year_level' => '3rd Year'],
            ['year_level' => '4th Year'],
            ['year_level' => '5th Year'],
        ];

        DB::table('year_levels')->insert($year_levels);
    }
}
