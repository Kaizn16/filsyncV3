<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoursesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $courses = [
            // CTE DEPARTMENT
            ['department_id' => 1, 'course_name' => 'Bachelor of Elementary Education', 'abbreviation' => 'BEEd'],
            ['department_id' => 1, 'course_name' => 'Bachelor of Secondary Education', 'abbreviation' => 'BSEd'],
            ['department_id' => 1, 'course_name' => 'Bachelor of Culture and Arts Education', 'abbreviation' => 'BCAEd'],
            ['department_id' => 1, 'course_name' => 'Bachelor of Early Childhood Education', 'abbreviation' => 'BECEd'],
            ['department_id' => 1, 'course_name' => 'Bachelor of Physical Education', 'abbreviation' => 'BPEd'],
            ['department_id' => 1, 'course_name' => 'Bachelor of Special Needs Education', 'abbreviation' => 'BSNEd'],
            // CBA DEPARTMENT
            ['department_id' => 2, 'course_name' => 'Bachelor of Science in Accountancy', 'abbreviation' => 'BSA'],
            ['department_id' => 2, 'course_name' => 'Bachelor of Science in Business Administration', 'abbreviation' => 'BSBA'],
            ['department_id' => 2, 'course_name' => 'Bachelor of Science in Intrepreneurship', 'abbreviation' => 'BSI'],
            // CAS DEPARTMENT
            ['department_id' => 3, 'course_name' => 'Bachelor of Arts', 'abbreviation' => 'BA'],
            ['department_id' => 3, 'course_name' => 'Bachelor of Science in Biology', 'abbreviation' => 'BS Bio'],
            ['department_id' => 3, 'course_name' => 'Bachelor of Science in Psychology', 'abbreviation' => 'BS Psych'],
            ['department_id' => 3, 'course_name' => 'Bachelor of Science in Social Work', 'abbreviation' => 'BSSW'],
            // COE DEPARMENT
            ['department_id' => 4, 'course_name' => 'Bachelor of Science in Electronics Engineering', 'abbreviation' => 'BSEE'],
            // CCS DEPARTMENT
            ['department_id' => 5, 'course_name' => 'Bachelor of Science in Computer Science', 'abbreviation' => 'BSCS'],
            ['department_id' => 5, 'course_name' => 'Bachelor of Science in Information Technology', 'abbreviation' => 'BSIT'],
            // CHTM DEPARTMENT
            ['department_id' => 6, 'course_name' => 'Bachelor of Science in Hospitality Management', 'abbreviation' => 'BSHM'],
            ['department_id' => 6, 'course_name' => 'Bachelor of Science in Tourism Management', 'abbreviation' => 'BSTM'],
            // CCJE DEPARTMENT
            ['department_id' => 7, 'course_name' => 'Bachelor of Science in Criminology', 'abbreviation' => 'BSCrim'],
            // CN DEPARTMENMT
            ['department_id' => 8, 'course_name' => 'Bachelor of Science in Nursing', 'abbreviation' => 'BSN'],
        ];

        DB::table('courses')->insert($courses);

    }
}
