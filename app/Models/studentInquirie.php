<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class studentInquirie extends Model
{
    use HasFactory;
    protected $table = 'student_inquiries';

    protected $fillable = [
        'id',
        'student_id',
        'subject',
        'message',
        'status',
        'inquirie_type',
        'resolved_at',
        'reply_message',
    ];
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
