<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    protected $fillable = [
        'patient_id',
        'service_id',
        'start_datetime',
        'end_datetime',
        'duration_minutes',
        'status',
        'notes',
        'reason_for_visit',
        'is_new_patient',
        'rating',
        'patient_feedback',
        'rated_at',
        'rescheduled_at',
        'original_datetime'
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'is_new_patient' => 'boolean',
        'duration_minutes' => 'integer',
        'rating' => 'integer',
        'rated_at' => 'datetime',
        'rescheduled_at' => 'datetime',
        'original_datetime' => 'datetime'
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    // Accessor for patient name
    public function getPatientNameAttribute()
    {
        if ($this->patient && $this->patient->info) {
            return trim($this->patient->info->first_name . ' ' . $this->patient->info->last_name);
        }
        return $this->patient ? $this->patient->name : 'Unknown Patient';
    }

    // Accessor for service name
    public function getServiceNameAttribute()
    {
        return $this->service ? $this->service->service_name : 'No Service';
    }
}
