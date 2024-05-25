<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SemesterTask extends Model
{
    use HasFactory;

    protected $table = 'semester_tasks';

    protected $fillable = [
        'id',
        'specialization_id',
        'course_id',
        'student_id',
        'semester_num',
        'academic_work_grade',
        'attendance',
        'midterm_grade',
        'practicality_grade',
        'final_grade',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
    public function specialization()
    {
        return $this->belongsTo(Specialization::class, 'specialization_id');
    }

}