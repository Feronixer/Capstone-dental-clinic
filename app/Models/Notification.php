<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'icon',
        'data',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Notification types constants
     */
    const TYPE_APPOINTMENT_CONFIRMED = 'appointment_confirmed';
    const TYPE_APPOINTMENT_REMINDER = 'appointment_reminder';
    const TYPE_APPOINTMENT_RESCHEDULED = 'appointment_rescheduled';
    const TYPE_APPOINTMENT_CANCELLED = 'appointment_cancelled';
    const TYPE_RECORD_UPDATED = 'record_updated';
    const TYPE_ANNOUNCEMENT = 'announcement';
    const TYPE_GENERAL = 'general';

    /**
     * Get the user that owns the notification
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(): void
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }

    /**
     * Mark notification as unread
     */
    public function markAsUnread(): void
    {
        $this->update([
            'is_read' => false,
            'read_at' => null,
        ]);
    }

    /**
     * Scope for unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for read notifications
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Scope for recent notifications
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', Carbon::now()->subDays($days));
    }

    /**
     * Get time ago format
     */
    public function getTimeAgoAttribute(): string
    {
        $diff = $this->created_at->diffForHumans();
        return $diff;
    }

    /**
     * Get icon based on notification type
     */
    public function getIconClassAttribute(): string
    {
        if ($this->icon) {
            return $this->icon;
        }

        return match($this->type) {
            self::TYPE_APPOINTMENT_CONFIRMED => 'bi-calendar-check',
            self::TYPE_APPOINTMENT_REMINDER => 'bi-bell',
            self::TYPE_APPOINTMENT_RESCHEDULED => 'bi-calendar-event',
            self::TYPE_APPOINTMENT_CANCELLED => 'bi-calendar-x',
            self::TYPE_RECORD_UPDATED => 'bi-file-earmark-medical',
            self::TYPE_ANNOUNCEMENT => 'bi-megaphone',
            default => 'bi-info-circle',
        };
    }

    /**
     * Get icon color based on notification type
     */
    public function getIconColorAttribute(): string
    {
        return match($this->type) {
            self::TYPE_APPOINTMENT_CONFIRMED => 'bg-success',
            self::TYPE_APPOINTMENT_REMINDER => 'bg-warning',
            self::TYPE_APPOINTMENT_RESCHEDULED => 'bg-info',
            self::TYPE_APPOINTMENT_CANCELLED => 'bg-danger',
            self::TYPE_RECORD_UPDATED => 'bg-primary',
            self::TYPE_ANNOUNCEMENT => 'bg-info',
            default => 'bg-secondary',
        };
    }
}
