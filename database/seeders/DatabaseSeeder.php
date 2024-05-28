<?php

namespace Database\Seeders;

use App\Models\College;
use App\Models\Course;
use App\Models\Specialization;
use App\Models\Student;
use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'admin',
            'username' => 'admin',
            'password' => bcrypt('123123123'),
            'user_type' => 'admin',
        ]);
        College::create([
            'name' => 'الحاسوب',
        ]);
        College::create([
            'name' => 'العلوم الادارية',
        ]);
        College::create([
            'name' => 'الطبية',
        ]);
        Specialization::create([
            'name' => "تقنية معلومات",
            'college_id' => 1,
            'Price' => "2000",
            'Number_of_years_of_study' => "4",
            'educational_qualification' => "ثانوية عامة علمي",
            'lowest_acceptance_rate' => "60",
        ]);
        Specialization::create([
            'name' => "نظم معلومات",
            'college_id' => 1,
            'Price' => "1800",
            'Number_of_years_of_study' => "4",
            'educational_qualification' => "ثانوية عامة علمي",
            'lowest_acceptance_rate' => "60",
        ]);
        Specialization::create([
            'name' => "امن سيبراني",
            'college_id' => 1,
            'Price' => "2400",
            'Number_of_years_of_study' => "4",
            'educational_qualification' => "ثانوية عامة علمي",
            'lowest_acceptance_rate' => "60",
        ]);
        Specialization::create([
            'name' => "ذكاء اصطناعي",
            'college_id' => 1,
            'Price' => "2300",
            'Number_of_years_of_study' => "4",
            'educational_qualification' => "ثانوية عامة علمي",
            'lowest_acceptance_rate' => "60",
        ]);
        Specialization::create([
            'name' => "علوم حاسوب",
            'college_id' => 1,
            'Price' => "1900",
            'Number_of_years_of_study' => "4",
            'educational_qualification' => "ثانوية عامة علمي",
            'lowest_acceptance_rate' => "60",
        ]);
        for ($i = 1; $i <= 5; $i++) {
            Course::create([
                'name' => 'عربي',
                'hours' => '2',
                "specialization_id" => $i,
                "semester_num" => '1',
            ]);
            Course::create([
                'name' => 'الصراع العربي الاسرائيلي',
                'hours' => '2',
                 "specialization_id" => $i,
                "semester_num" => '1',
            ]);
            Course::create([
                'name' => 'الثقافة الاسلامية',
                'hours' => '2',
                 "specialization_id" => $i,
                "semester_num" => '2',
            ]);
            Course::create([
                'name' => 'عربي 2',
                'hours' => '2',
                 "specialization_id" => $i,
                "semester_num" => '2',
            ]);
            Course::create([
                'name' => 'رياضيات',
                'hours' => '3',
                 "specialization_id" => $i,
                "semester_num" => '1',
            ]);
            Course::create([
                'name' => 'رياضيات 2',
                'hours' => '3',
                 "specialization_id" => $i,
                "semester_num" => '2',
            ]);
            Course::create([
                'name' => 'اساسيات الحاسوب',
                'hours' => '3',
                 "specialization_id" => $i,
                "semester_num" => '1',
            ]);
        }
        Student::create([
            'full_name'=>"اسامة عبدالله حميد زايد",
            'personal_id'=>"123123123",
            'academic_id'=>"123123123",
            'phone_number'=>"775561590",
            'relative_phone_number'=>"775561590",
            'date_of_birth'=>"2002/8/8",
            'place_of_birth'=>"صنعاء",
            'gender'=>"ذكر",
            'nationality'=>"يمني",
            'educational_qualification'=>"ثانوية عامة علمي",
            'high_school_grade'=>"73",
            'school_graduation_date'=>"2020/1/1",
            'discount_percentage'=>"40",
            'college_id'=>"1",
            'specialization_id'=>"1",
            'password'=>bcrypt('123123123'),
            'semester_num'=>"1",
            'image'=>'images/Student/_78b81fc1-26f1-40f7-bb07-3ebda3b44b43.jpg',
        ]);
        Student::create([
            'full_name'=>"زيد ثابت محمد مهدي",
            'personal_id'=>"111222333",
            'academic_id'=>"111222333",
            'phone_number'=>"774814210",
            'relative_phone_number'=>"774814210",
            'date_of_birth'=>"2000/3/5",
            'place_of_birth'=>"صنعاء",
            'gender'=>"ذكر",
            'nationality'=>"يمني",
            'educational_qualification'=>"ثانوية عامة علمي",
            'high_school_grade'=>"70",
            'school_graduation_date'=>"2018/1/1",
            'discount_percentage'=>"40",
            'college_id'=>"1",
            'specialization_id'=>"1",
            'password'=>bcrypt('123123123'),
            'semester_num'=>"1",
            'image'=>'images/Student/_78b81fc1-26f1-40f7-bb07-3ebda3b44b43.jpg',
        ]);
    }
}
