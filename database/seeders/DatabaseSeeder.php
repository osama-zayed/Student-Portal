<?php

namespace Database\Seeders;

use App\Models\College;
use App\Models\CollegeNew;
use App\Models\Course;
use App\Models\SchoolYear;
use App\Models\semesterNumber;
use App\Models\Specialization;
use App\Models\Student;
use App\Models\Teacher;
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
        semesterNumber::create([
            'name' => 'الاول'
        ]);
        semesterNumber::create([
            'name' => 'الثاني'
        ]);
        semesterNumber::create([
            'name' => 'الثالث'
        ]);
        semesterNumber::create([
            'name' => 'الرابع'
        ]);
        semesterNumber::create([
            'name' => 'الخامس'
        ]);
        semesterNumber::create([
            'name' => 'السادس'
        ]);
        semesterNumber::create([
            'name' => 'السابع'
        ]);
        semesterNumber::create([
            'name' => 'الثامن'
        ]);
        semesterNumber::create([
            'name' => 'التاسع'
        ]);
        semesterNumber::create([
            'name' => 'العاشر'
        ]);
        semesterNumber::create([
            'name' => 'الحادي عشر'
        ]);
        semesterNumber::create([
            'name' => 'الثاني عشر'
        ]);
        // semesterNumber::create([
        //     'name'=>'الثالث عشر'
        // ]);
        // semesterNumber::create([
        //     'name'=>'الرابع عشر'
        // ]);
        SchoolYear::create([
            'name' => 'طوفان المعلومات',
            'start_date' => '2024-01-01',
            'end_date' => '2024-10-01',
            'is_current' => false,
        ]);
        SchoolYear::create([
            'name' => 'عام النكبة',
            'start_date' => '2025-01-01',
            'end_date' => '2025-10-01',
            'is_current' => true,
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
            'Number_of_semester_of_study' => "8",
            'educational_qualification' => "ثانوية عامة علمي",
            'lowest_acceptance_rate' => "60",
        ]);
        Specialization::create([
            'name' => "نظم معلومات",
            'college_id' => 1,
            'Price' => "1800",
            'Number_of_years_of_study' => "4",
            'Number_of_semester_of_study' => "8",
            'educational_qualification' => "ثانوية عامة علمي",
            'lowest_acceptance_rate' => "60",
        ]);
        Specialization::create([
            'name' => "امن سيبراني",
            'college_id' => 1,
            'Price' => "2400",
            'Number_of_years_of_study' => "4",
            'Number_of_semester_of_study' => "8",
            'educational_qualification' => "ثانوية عامة علمي",
            'lowest_acceptance_rate' => "60",
        ]);
        Specialization::create([
            'name' => "ذكاء اصطناعي",
            'college_id' => 1,
            'Price' => "2300",
            'Number_of_years_of_study' => "4",
            'Number_of_semester_of_study' => "8",
            'educational_qualification' => "ثانوية عامة علمي",
            'lowest_acceptance_rate' => "60",
        ]);
        Specialization::create([
            'name' => "علوم حاسوب",
            'college_id' => 1,
            'Price' => "1900",
            'Number_of_years_of_study' => "4",
            'Number_of_semester_of_study' => "8",
            'educational_qualification' => "ثانوية عامة علمي",
            'lowest_acceptance_rate' => "60",
        ]);
        Teacher::create([
            'name' => 'خالد ضيف الله',
            'qualification' => 'دكتوراه',
            'gender' => 'ذكر',
            'phone_number' => '777777777',
            'address' => 'بيت بوس',
        ]);
        Teacher::create([
            'name' => 'سماح شاكر',
            'qualification' => 'دكتوراه',
            'gender' => 'انثى',
            'phone_number' => '777777777',
            'address' => 'بيت بوس',
        ]);
        Teacher::create([
            'name' => 'حسان مثنى',
            'qualification' => 'دكتوراه',
            'gender' => 'ذكر',
            'phone_number' => '777777777',
            'address' => 'بيت بوس',
        ]);
        Teacher::create([
            'name' => 'هشام حيدر',
            'qualification' => 'دكتوراه',
            'gender' => 'ذكر',
            'phone_number' => '777777777',
            'address' => 'بيت بوس',
        ]);
        for ($i = 1; $i <= 5; $i++) {
            Course::create([
                'name' => 'عربي',
                'hours' => '2',
                "specialization_id" => $i,
                "semester_num" => '1',
                "teachers_id" => '1',
            ]);
            Course::create([
                'name' => 'الصراع العربي الاسرائيلي',
                'hours' => '2',
                "specialization_id" => $i,
                "semester_num" => '1',
                "teachers_id" => '2',
            ]);
            Course::create([
                'name' => 'الثقافة الاسلامية',
                'hours' => '2',
                "specialization_id" => $i,
                "semester_num" => '2',
                "teachers_id" => '2',
            ]);
            Course::create([
                'name' => 'عربي 2',
                'hours' => '2',
                "specialization_id" => $i,
                "semester_num" => '2',
                "teachers_id" => '1',
            ]);
            Course::create([
                'name' => 'رياضيات',
                'hours' => '3',
                "specialization_id" => $i,
                "semester_num" => '1',
                "teachers_id" => '3',
            ]);
            Course::create([
                'name' => 'رياضيات 2',
                'hours' => '3',
                "specialization_id" => $i,
                "semester_num" => '2',
                "teachers_id" => '3',
            ]);
            Course::create([
                'name' => 'اساسيات الحاسوب',
                'hours' => '3',
                "specialization_id" => $i,
                "semester_num" => '1',
                "teachers_id" => '4',
            ]);
        }
        Student::create([
            'full_name' => "اسامة عبدالله حميد زايد",
            'personal_id' => "123123123",
            'academic_id' => "123123123",
            'phone_number' => "775561590",
            'relative_phone_number' => "775561590",
            'date_of_birth' => "2002/8/8",
            'place_of_birth' => "صنعاء",
            'gender' => "ذكر",
            'nationality' => "يمني",
            'educational_qualification' => "ثانوية عامة علمي",
            'high_school_grade' => "73",
            'school_graduation_date' => "2020/1/1",
            'discount_percentage' => "40",
            'college_id' => "1",
            'specialization_id' => "1",
            'password' => bcrypt('123123123'),
            'semester_num' => "1",
            'academic_year' => "1",
            'image' => 'images/Student/Male.jpg',
        ]);
        Student::create([
            'full_name' => "زيد ثابت محمد مهدي",
            'personal_id' => "111222333",
            'academic_id' => "111222333",
            'phone_number' => "774814210",
            'relative_phone_number' => "774814210",
            'date_of_birth' => "2000/3/5",
            'place_of_birth' => "صنعاء",
            'gender' => "ذكر",
            'nationality' => "يمني",
            'educational_qualification' => "ثانوية عامة علمي",
            'high_school_grade' => "70",
            'school_graduation_date' => "2018/1/1",
            'discount_percentage' => "40",
            'college_id' => "1",
            'specialization_id' => "1",
            'password' => bcrypt('123123123'),
            'semester_num' => "1",
            'academic_year' => "1",
            'image' => 'images/Student/Male.jpg',
        ]);
        CollegeNew::create([
            'title' => 'كلية طب الأسنان تقيم فعالية التغذية بعنوان (نصائح لتحسين صحتك)',
            'image' => 'CollegeNew/af86b0aa-dd5e-43c4-b622-c9f28f23d4e119.jpg',
            "description" => 'برعاية رئيس الجامعة أ. د عمرو أحمد النجار أقامت كلية طب الأسنان تقيم فعالية التغذية بعنوان (نصائح لتحسين صحتك)) (بإشراف الدكتورة/ فاطمة بامشموس، لطلاب المستوى الثاني

            حضر الفعالية د/ علوي أحمد النجار نائب رئيس الجامعة لشئون الطلاب ، و أ. د/ غمدان الحرازي عميد الكلية ، وأ. د/ أنس المحبشي عميد كلية العلوم الطبية ورؤساء الأقسام في كلية طب الأسنان:
            
            د. منال الحجري، د. سارة الراعي، د. عباس الكبس، د. سام داعر
            
            والتى هدفت إلى الوقاية بالتغذية من:
            
            - أمراض القلب.
            
            - السمنه المفرطة.
            
            - التشوهات السنية وتسوس الأسنان.
            
            - أمراض الغدة الدرقية.
            
            وقد حضر المعرض عدد غفير من الزوار وأولياء أمور الطلاب وبعض طلاب مدارس أمانة العاصمة.
            
            #إعلام_الجامعة
            
            #إدارة_العلاقات_العامة_والإعلام',
        ]);
        CollegeNew::create([
            'title' => 'أقامت كلية طب الأسنان معرضاً توعوياً بسرطان الفم',
            'image' => 'CollegeNew/cf3a06ac-43d9-49a7-a60b-c2e3654996b64.jpg',
            "description" => 'برعاية رئيس الجامعة أ. د عمرو أحمد النجار أقامت كلية طب الأسنان معرضاً توعوياً بسرطان الفم بإشراف الدكتورة/ ليلى حافظ استشاري أمراض الفم والوجه والفكين، لطلاب المستوى الثالث، وحضر المعرض التوعوي د/ علوي أحمد النجار نائب رئيس الجامعة وأ. د/ غمدان الحرازي عميد الكلية، و أ. د/ أنس المحبشي عميد كلية العلوم الطبية، ورؤساء الأقسام في كلية طب الأسنان :د. منال الحجري، د. سارة الراعي، د. عباس الكبس، د. سام داعر

            وهدف المعرض التوعوي إلى:-
            
            التوعية بسرطان الفم.
            
            - أسباب سرطان الفم.
            
            - التغذية الصحية والغير صحية.
            
            - تأثير التبغ والتدخين والقات.
            
            - فوائد الكشف المبكر.
            
            وقد حضر المعرض عدد غفير من الزوار وأولياء أمور الطلاب وبعض طلاب مدارس أمانة العاصمة.
            
            #جامعة_سبأ
            
            #إعلام_الجامعة
            
            #إدارة_العلاقات_العامة_والاعلام',
        ]);
        CollegeNew::create([
            'title' => 'دورة بعنوان: "التعلم للحياة STEAM" لقيادات المدارس في أمانة العاصمة',
            'image' => 'CollegeNew/66635c0f-66ef-4438-9930-3683fcdcf39732.jpg',
            "description" => 'تحقيقاً لرؤية الجامعة وخطتها الاستراتيجية عقدت جامعة سبأ يوم الخميس الموافق 17/08/2023 في مبنى عمادة الدراسات العليا دورة بعنوان: "التعلم للحياة STEAM" لقيادات المدارس في أمانة العاصمة.

            وتهدف هذه الدورة إلى رفع المهارات المتعلقة بربط التعليم والتعلم بالحياة الواقعية وكيف يمكن الربط بين المقررات الدراسية ومحتوى المناهج الدراسية في جميع التخصصات وبين الواقع العملي بحيث تكون هناك علاقة بين التعليم والمجتمع وسوق العمل.
            
            أقام الدورة د. خالد محسن زهير رئيس قسم العلوم الإنسانية بجامعة سبأ.
            
            وقد تم تناول موضوعات كثيره كان من أهمها:
            
            - تعريف "التعليم للحياة STEAM".
            
            - أهداف وأهمية ومميزات هذه الرؤية.
            
            - استراتيجيات التعليم والتعلم وفق هذه الرؤية.
            
            - بعض النماذج للتعليم والتعلم وفق هذه الرؤية.
            
            - كيف يمكن موائمة مناهجنا الحالية لما يخدم هذه الرؤية.
            
             
            
            وكان الجزء الثاني من الجلسة عبارة عن نقاش في مجموعات تعلم تعاوني لمناقشة وإثراء هذا الموضوع وكيفية ربط التعليم بالحياة مع ذكر أمثلة واقعية تطبيقية في كل تخصصات المشاركين.
            
            وقد تم اختتام الدورة بتوزيع شهادات المشاركة على الحاضرين.
            
             
            
            #جامعة_سبأ
            
            #العلاقات_العامة',
        ]);
    }
}
