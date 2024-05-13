<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseSpecialization extends Model
{
    use HasFactory;

    protected $table = 'course_specialization';

    protected $fillable = ['id','specialization_id', 'course_id', 'semester_num'];

    public function specialization()
    {
        return $this->belongsTo(Specialization::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}