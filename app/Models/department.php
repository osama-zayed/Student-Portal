<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class department extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'name','phone_number'];
    protected $table = 'departments';
    public function users()
    {
        return $this->hasMany(User::class,"department_id");
    }
}
