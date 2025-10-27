<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgressNote extends Model
{
    protected $fillable = [
        'patient_record_id',
        'note_date',
        'progress_description',
        'treatment_response',
        'next_steps',
        'status'
    ];

    protected $casts = [
        'note_date' => 'date'
    ];

    // Relationships
    public function patientRecord()
    {
        return $this->belongsTo(PatientRecord::class);
    }
}
