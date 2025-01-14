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
        
        $courses_no = [
            // College of Teachers Education
            [
                'department_id' => 1, 
                'course_id' => '1', // BEEd
                'course_no' => 'CTE101', 
                'descriptive_title' => 'Foundations of Education',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'E1', 
                'descriptive_title' => 'Introduction to Linguistics',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'E2', 
                'descriptive_title' => 'Language, Culture, and Society',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'E3', 
                'descriptive_title' => 'Stracture of English',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'E4', 
                'descriptive_title' => 'Principiles and Theories of Language Acquisition and Learning',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'E5', 
                'descriptive_title' => 'Language Programs and Policies in Multilingual Societies',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'E6', 
                'descriptive_title' => 'Children and Adolescents Learners and Learning Principles',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'E7', 
                'descriptive_title' => 'Teaching and Assesment of Literature Studies',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'E8', 
                'descriptive_title' => 'Teaching and Assesment of the Macroskills',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'E9', 
                'descriptive_title' => 'Teaching and Assesment of Grammar',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'E10', 
                'descriptive_title' => 'Mythology and Folklore',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'E11', 
                'descriptive_title' => 'Survey of Philppine Literature in English',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'E12', 
                'descriptive_title' => 'Survey of Afro-Asian Literature in English',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'E13', 
                'descriptive_title' => 'Technical Writing',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'E14', 
                'descriptive_title' => 'Speech and Theater Arts',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'E15', 
                'descriptive_title' => 'Contemporary, Popular and Emergent Literature',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'E16', 
                'descriptive_title' => 'Survey of Englisng and American Literature',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'E17', 
                'descriptive_title' => 'Language Education Research',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'E18', 
                'descriptive_title' => 'Language Learning Materials Development',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'E19', 
                'descriptive_title' => 'Campus Journalism',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'E20', 
                'descriptive_title' => 'Technology for Teaching and Learning 2 (Technology for Teaching and Learning)',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'E21', 
                'descriptive_title' => 'Language Education Research 2',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'E22', 
                'descriptive_title' => 'Literagy Criticism',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'GE1', 
                'descriptive_title' => 'Understanding the Self',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'GE2', 
                'descriptive_title' => 'Readings in Philippine History',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'GE3', 
                'descriptive_title' => 'The Contemporary World',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'GE4', 
                'descriptive_title' => 'Mathematics in the Modern World',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'GE5', 
                'descriptive_title' => 'Purposive Communication',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'GE6', 
                'descriptive_title' => 'Art Appreciation',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'GE7', 
                'descriptive_title' => 'Science, Technology, and Society',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'GE8', 
                'descriptive_title' => 'Ethics',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'GE9', 
                'descriptive_title' => 'Living in the IT Era',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'GE10', 
                'descriptive_title' => 'Indigenous People and Peace Studies Education',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'GE11', 
                'descriptive_title' => 'Gender and Society',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'GE12', 
                'descriptive_title' => 'Life and Works of Rizal',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'FED1', 
                'descriptive_title' => 'The Teaching Profession',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'FED2', 
                'descriptive_title' => 'The Child and Adolescent Learners and Learning Principles',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'FED3', 
                'descriptive_title' => 'Foundation of Special and Inclusive Education',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'FED4', 
                'descriptive_title' => 'The Teacher and the Community, School Culture and Organizational Leadership',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'PCK1', 
                'descriptive_title' => 'Facilitating Learner-Centered Teaching',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'PCK2', 
                'descriptive_title' => 'Assesment in Learning 1',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'PCK3', 
                'descriptive_title' => 'Technology for Teaching and Learning 1',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'PCK4', 
                'descriptive_title' => 'Assesment Learning 2',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'PCK5', 
                'descriptive_title' => 'The Teacher and the School Curriculum',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'PCK6', 
                'descriptive_title' => 'Building and Enhancing New Literacies Across the Curriculum',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'BIB1', 
                'descriptive_title' => 'Understanding the Old Testament',
                'credits' => 3,
            ],[
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'BIB2', 
                'descriptive_title' => 'Understanding the New Testament',
                'credits' => 3,
            ],[
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'EEL1', 
                'descriptive_title' => 'Translation',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'EEL2', 
                'descriptive_title' => 'Creative Writing',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'EL1', 
                'descriptive_title' => 'Field Study 1',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'EL2', 
                'descriptive_title' => 'Field Study 2',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'EL3', 
                'descriptive_title' => 'Teaching Internship',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'EN1', 
                'descriptive_title' => 'Enhancement Course 1',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'EN2', 
                'descriptive_title' => 'Enhancement Course 2',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'PE1', 
                'descriptive_title' => 'PATH-FIT 1: Movement Compentency Training',
                'credits' => 2,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'PE2', 
                'descriptive_title' => 'PATH-FIT 2: Exercise-Based Fitness Activities',
                'credits' => 2,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'PE3', 
                'descriptive_title' => 'PATH-FIT 3: Dance',
                'credits' => 2,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'PE4', 
                'descriptive_title' => 'PATH-FIT 2: Martial Arts',
                'credits' => 2,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BSEd
                'course_no' => 'NSTP1', 
                'descriptive_title' => 'National Service Training Program 1',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '2', // BESd
                'course_no' => 'NSTP2', 
                'descriptive_title' => 'National Service Training Program 2',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '3', // BCAEd
                'course_no' => 'CTE103', 
                'descriptive_title' => 'Art in Early Childhood Education',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '4', // BECEd
                'course_no' => 'CTE104', 
                'descriptive_title' => 'Child Development and Learning',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '5', // BPEd
                'course_no' => 'CTE105', 
                'descriptive_title' => 'Physical Education and Sports Science',
                'credits' => 3,
            ],
            [
                'department_id' => 1, 
                'course_id' => '6', // BSNEd
                'course_no' => 'CTE106', 
                'descriptive_title' => 'Introduction to Special Education',
                'credits' => 3,
            ],
        
            // College of Business and Accountancy
            [
                'department_id' => 2,
                'course_id' => '7',  // BSA
                'course_no' => 'CBA201', 
                'descriptive_title' => 'Financial Accounting',
                'credits' => 3,
            ],
            [
                'department_id' => 2,
                'course_id' => '8',  // BSBA
                'course_no' => 'CBA202', 
                'descriptive_title' => 'Marketing Management',
                'credits' => 3,
            ],
            [
                'department_id' => 2,
                'course_id' => '9',  // BSI
                'course_no' => 'CBA203', 
                'descriptive_title' => 'Investment Analysis',
                'credits' => 3,
            ],
            
            // College of Arts and Sciences
            [
                'department_id' => 3,
                'course_id' => '10', // BA
                'course_no' => 'CAS301', 
                'descriptive_title' => 'Philippine Literature',
                'credits' => 3,
            ],
            [
                'department_id' => 3,
                'course_id' => '11', // BS Bio
                'course_no' => 'CAS302', 
                'descriptive_title' => 'General Biology',
                'credits' => 3,
            ],
            [
                'department_id' => 3,
                'course_id' => '12', // BS Psych
                'course_no' => 'CAS303', 
                'descriptive_title' => 'Introduction to Psychology',
                'credits' => 3,
            ],
            [
                'department_id' => 3,
                'course_id' => '13', // BSSW
                'course_no' => 'CAS304', 
                'descriptive_title' => 'Social Welfare Policy',
                'credits' => 3,
            ],
        
            // College of Engineering
            [
                'department_id' => 4,
                'course_id' => '14', // BSEE
                'course_no' => 'COE401', 
                'descriptive_title' => 'Circuit Analysis',
                'credits' => 3,
            ],
        
            // College of Computer Studies
            [
                'department_id' => 5,
                'course_id' => '15',  // BSCS
                'course_no' => 'CCS501', 
                'descriptive_title' => 'Data Structures and Algorithms',
                'credits' => 3,
            ],
            [
                'department_id' => 5,
                'course_id' => '16',  // BSIT
                'course_no' => 'CCS502', 
                'descriptive_title' => 'Information Technology Fundamentals',
                'credits' => 3,
            ],
        
            // College of Hospitality, Tourism and Management
            [
                'department_id' => 6,
                'course_id' => '17', // BSHM
                'course_no' => 'CHTM601', 
                'descriptive_title' => 'Introduction to Hospitality Management',
                'credits' => 3,
            ],
            [
                'department_id' => 6,
                'course_id' => '18', // BSTM
                'course_no' => 'CHTM602', 
                'descriptive_title' => 'Tourism Planning and Development',
                'credits' => 3,
            ],
        
            // College of Criminal Justice Education
            [
                'department_id' => 7,
                'course_id' => '19', // BSCrim
                'course_no' => 'CCJE701', 
                'descriptive_title' => 'Criminal Law and Procedure',
                'credits' => 3,
            ],
        
            // College of Nursing
            [
                'department_id' => 8,
                'course_id' => '20', // BSN
                'course_no' => 'CON801', 
                'descriptive_title' => 'Fundamentals of Nursing',
                'credits' => 3,
            ],
        ];
        
        DB::table('courses_no')->insert($courses_no);

    }
}
