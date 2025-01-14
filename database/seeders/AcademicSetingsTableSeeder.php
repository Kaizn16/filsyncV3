<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AcademicSetingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $current_year = Carbon::now()->year;
        $next_year = Carbon::now()->addYear()->year;
        $semester = '1st Semester';

        $academic_setting = [
            ['start_year' => $current_year, 'end_year' => $next_year, 'semester' => $semester],
        ];

        DB::table('academic_settings')->insert($academic_setting);
    }
}
