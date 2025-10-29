<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppointmentRequest extends Model
{
    protected $fillable = [
        'patient_id',
        'service_id',
        'other_concern',
        'existing_appointment_id',
        'request_type',
        'requested_datetime',
        'requested_end_datetime',
        'duration_minutes',
        'reason',
        'notes',
        'status',
        'reviewed_by',
        'review_notes',
        'reviewed_at'
    ];

    protected $casts = [
        'requested_datetime' => 'datetime',
        'requested_end_datetime' => 'datetime',
        'reviewed_at' => 'datetime',
        'duration_minutes' => 'integer',
    ];

    // Relationships
    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function existingAppointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class, 'existing_appointment_id');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // Helper methods
    public function isPending(): bool
    {
        return $this->status === 'Pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'Approved';
    }

    public function isDenied(): bool
    {
        return $this->status === 'Denied';
    }

    public function isWalkIn(): bool
    {
        return $this->request_type === 'walk-in';
    }

    public function isReschedule(): bool
    {
        return $this->request_type === 'reschedule';
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'Approved');
    }

    public function scopeDenied($query)
    {
        return $query->where('status', 'Denied');
    }

    public function scopeWalkIn($query)
    {
        return $query->where('request_type', 'walk-in');
    }

    public function scopeReschedule($query)
    {
        return $query->where('request_type', 'reschedule');
    }
}
