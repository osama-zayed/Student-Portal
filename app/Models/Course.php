<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $table = 'courses';

    protected $fillable = ['id','name', 'hours'];

    public function specializations()
    {
        return $this->belongsToMany(Specialization::class, 'course_specialization', 'course_id', 'specialization_id')->withTimestamps()->withPivot('semester_num');
    }
    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'course_teacher', 'course_id', 'teacher_id')->withTimestamps();
    }
}
