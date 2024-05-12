<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class College extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'name'];
    protected $table = 'college';
    public function users()
    {
        return $this->hasMany(User::class,"department_id");
    }
}
