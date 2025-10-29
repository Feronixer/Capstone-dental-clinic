<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientRecord extends Model
{
    protected $fillable = [
        'user_id',
        'appointment_id',
        'patient_number',
        'home_address',
        'date_of_birth',
        'age',
        'sex',
        'nickname',
        'religion',
        'occupation',
        'contact',
        'guardian_name',
        'guardian_contact',
        'guardian_occupation',
        'other_notes',
        'medical_history',
        'allergies',
        'current_medications',
        'chief_complaint',
        'diagnosis',
        'treatment_plan',
        'previous_dentist',
        'last_dental_visit',
        'treatment_done',
        'physician_name',
        'physician_specialty',
        'physician_office_address',
        'physician_contact',
        'health_questions',
        'allergies_detail',
        'is_pregnant',
        'is_nursing',
        'takes_birth_control',
        'sent_to_patient',
        'sent_at'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'last_dental_visit' => 'date',
        'health_questions' => 'array',
        'allergies_detail' => 'array',
        'is_pregnant' => 'boolean',
        'is_nursing' => 'boolean',
        'takes_birth_control' => 'boolean',
        'sent_to_patient' => 'boolean',
        'sent_at' => 'datetime'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function patientHistories()
    {
        return $this->hasMany(PatientHistory::class);
    }

    public function progressNotes()
    {
        return $this->hasMany(ProgressNote::class);
    }

    // Scope: filter records by user id
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }
}
