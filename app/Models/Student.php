<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';

    protected $fillable = [
        'id',
        'full_name',
        'personal_id',
        'academic_id',
        'phone_number',
        'relative_phone_number',
        'date_of_birth',
        'place_of_birth',
        'gender',
        'high_school_grade',
        'college_id',
        'specialization_id',
        'password',
        'semester_num',
    ];

    public function college()
    {
        return $this->belongsTo(College::class, 'college_id');
    }

    public function specialization()
    {
        return $this->belongsTo(Specialization::class, 'specialization_id');
    }
}