<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    protected $table = 'results';

    protected $fillable = [
        'id',
        'student_id',
        'semester_tasks_id',
        'semester_num',
        'course_id',
        'academic_work_grade',
        'final_exam_grade',
        'final_grade',
        'status',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
    public function semester_tasks()
    {
        return $this->belongsTo(SemesterTask::class, 'semester_tasks_id');
    }
}