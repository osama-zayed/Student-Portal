<?php

namespace Database\Seeders;

use App\Models\College;
use App\Models\Specialization;
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
    }
}
