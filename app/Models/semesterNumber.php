<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class semesterNumber extends Model
{
    use HasFactory;
    protected $table = 'semester_numbers';

    protected $fillable = [
        'id',
        'name'
    ];

}
