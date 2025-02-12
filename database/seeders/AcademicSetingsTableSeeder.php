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
        $last_year = Carbon::now()->subYear()->year;
        $current_year = Carbon::now()->year;
        $semester = '2nd Semester';

        $academic_setting = [
            ['academic_year' => $last_year . '-' . $current_year, 'semester' => $semester],
        ];

        DB::table('academic_settings')->insert($academic_setting);
    }

}
