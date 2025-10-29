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

    // Scope: filter progress notes by user id
    public function scopeForUser($query, int $userId)
    {
        return $query->whereHas('patientRecord', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }
}
