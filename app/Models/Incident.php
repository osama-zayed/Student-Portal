<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Incident extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'incidents';
    protected $fillable = [
        'id',
        'incident_number',
        'crime_type_id',
        'incident_date',
        'department_id',
        'incident_time',
        'date_occurred',
        'incident_location',
        'reasons_and_motives',
        'tools_used',
        'number_of_victims',
        'number_of_perpetrators',
        'incident_status',
        'incident_description',
        'incident_image',
        'notes',
        'main_incident_id',
    ];
    
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function crimeType()
    {
        return $this->belongsTo(Crime::class, 'crime_type_id');
    }
}