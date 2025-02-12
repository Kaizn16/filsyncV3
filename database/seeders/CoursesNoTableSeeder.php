<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoursesNoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        $csvFile = storage_path('CSV/courses_no.csv');

        if (($handle = fopen($csvFile, 'r')) !== false) {
            fgetcsv($handle, 5000, ',');
            while (($row = fgetcsv($handle, 5000, ',')) !== false) {
                DB::table('courses_no')->insert([
                    'department_id' => $row[0],
                    'course_id' => $row[1],
                    'course_no' => $row[2],
                    'descriptive_title' => $row[3],
                    'credits' => $row[4],
                    'year_level' => $row[5],
                    'semester' => $row[6],
                ]);
            }

            fclose($handle);
        } else {
            echo "Error: Unable to open the CSV file.";
        }

    }
}
