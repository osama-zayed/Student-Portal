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
    protected $table = 'specialization';
}
