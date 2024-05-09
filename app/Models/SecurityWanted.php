<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SecurityWanted extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'security_wanteds';

    protected $fillable = [
        'id',
        'registration_number',
        'day',
        'registration_date',
        'wanted_name',
        'age',
        'event',
        'gender',
        'marital_status',
        'nationality',
        'occupation',
        'place_of_birth',
        'residence',
        'previous_convictions'
    ];
}