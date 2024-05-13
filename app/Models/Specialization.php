<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialization extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'college_id',
        'Price',
        'Number_of_years_of_study',
    ];

    protected $table = 'specializations';

    public function college()
    {
        return $this->belongsTo(College::class, 'college_id');
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_specialization', 'specialization_id', 'course_id')->withTimestamps()->withPivot('semester_num');
    }
    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
