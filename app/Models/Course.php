<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $table = 'courses';

    protected $fillable = [
        'id',
        'name',
        'hours',
        "specialization_id",
        "semester_num",
        "teachers_id",
    ];
        public function specialization()
    {
        return $this->belongsTo(Specialization::class);
    }

    public function teachers()
    {
        return $this->belongsTo(Teacher::class);
    }
}
