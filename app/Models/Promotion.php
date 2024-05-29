<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'student_id',
        'from_semester_num',
        'to_semester_num',
        'from_specialization_id',
        'to_specialization_id',
        'academic_year',
        'academic_year_new',
        'status',
    ];
    protected $table = 'promotions';

}