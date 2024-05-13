<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class College extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'name'];
    protected $table = 'colleges';
    public function Specialization()
    {
        return $this->hasMany(Specialization::class);
    }
    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
