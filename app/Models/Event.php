<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'event_date',
        'event_time',
        'location',
        'event_type',
        'is_active',
        'image_path'
    ];

    protected $casts = [
        'event_date' => 'date',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get formatted event date
     */
    public function getFormattedDateAttribute()
    {
        return Carbon::parse($this->event_date)->format('F d, Y');
    }

    /**
     * Get formatted event time
     */
    public function getFormattedTimeAttribute()
    {
        return $this->event_time ? Carbon::parse($this->event_time)->format('g:i A') : null;
    }

    /**
     * Check if event is upcoming
     */
    public function isUpcoming()
    {
        return $this->event_date >= now()->toDateString();
    }

    /**
     * Scope for active events
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for upcoming events
     */
    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', now()->toDateString())
                    ->orderBy('event_date', 'asc');
    }

    /**
     * Scope for past events
     */
    public function scopePast($query)
    {
        return $query->where('event_date', '<', now()->toDateString())
                    ->orderBy('event_date', 'desc');
    }
}
