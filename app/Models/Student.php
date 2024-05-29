<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Activitylog\Traits\CausesActivity;

class Student extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, CausesActivity;

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
        'nationality',
        'educational_qualification',
        'high_school_grade',
        'school_graduation_date',
        'discount_percentage',
        'college_id',
        'specialization_id',
        'password',
        'semester_num',
        'user_status',
        'academic_year',
        'status',
        'image',
    ];

    public function college()
    {
        return $this->belongsTo(College::class, 'college_id');
    }

    public function specialization()
    {
        return $this->belongsTo(Specialization::class, 'specialization_id');
    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}