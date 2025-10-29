<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientHistory extends Model
{
    protected $fillable = [
        'patient_record_id',
        'visit_date',
        // Dental History
        'previous_dentist',
        'last_dental_visit',
        'treatment_done',
        // Medical History
        'physician_name',
        'physician_specialty',
        'physician_office_address',
        'physician_contact',
        // Health Questions
        'good_health',
        'under_treatment',
        'treatment_condition',
        'serious_illness',
        'illness_details',
        'been_hospitalized',
        'hospitalization_reason',
        'taking_drugs',
        'medications',
        'tobacco_use',
        'alcohol_use',
        'recreational_drugs',
        // Allergies
        'allergy_anesthesia',
        'allergy_sulfa',
        'allergy_antibiotics',
        'allergy_aspirin',
        'allergy_analgesics',
        'allergy_latex',
        'food_allergy_details',
        'other_allergy_details',
        // For Women
        'is_pregnant',
        'is_nursing',
        'birth_control',
        // Procedure Details
        'procedure_performed',
        'materials_used',
        'anesthesia_used',
        'complications',
        'post_operative_instructions',
        'follow_up_notes',
        // Auto-send fields
        'sent_to_patient',
        'sent_at'
    ];

    protected $casts = [
        'visit_date' => 'date'
    ];

    // Expose computed attributes in JSON
    protected $appends = [
        'user_id'
    ];

    // Relationships
    public function patientRecord()
    {
        return $this->belongsTo(PatientRecord::class);
    }

    // Convenience: get the owning user's id via patient record
    public function getUserIdAttribute(): ?int
    {
        return $this->patientRecord ? $this->patientRecord->user_id : null;
    }

    // Scope: filter histories by user id
    public function scopeForUser($query, int $userId)
    {
        return $query->whereHas('patientRecord', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }
}
