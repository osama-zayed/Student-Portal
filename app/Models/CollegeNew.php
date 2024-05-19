<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollegeNew extends Model
{
    use HasFactory;
    protected $table='college_news';
    
    protected $fillable = [
        'id',
        'title',
        'image',
        "description",
    ];
   
}
