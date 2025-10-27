<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientHistory extends Model
{
    protected $fillable = [
        'patient_record_id',
        'visit_date',
        'procedure_performed',
        'materials_used',
        'anesthesia_used',
        'complications',
        'post_operative_instructions',
        'follow_up_notes'
    ];

    protected $casts = [
        'visit_date' => 'date'
    ];

    // Relationships
    public function patientRecord()
    {
        return $this->belongsTo(PatientRecord::class);
    }
}
