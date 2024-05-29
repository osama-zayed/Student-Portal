<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'qualification',
        'gender',
        'phone_number',
        'address',
        'status',
    ];

    protected $table = 'promotions';

  
}