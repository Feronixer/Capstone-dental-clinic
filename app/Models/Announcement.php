<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'subheading',
        'content',
        'image_path',
        'ticker_text',
        'show_ticker',
        'is_active',
        'date_start',
        'date_end',
        'time_start',
        'time_end',
        'is_whole_day'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'show_ticker' => 'boolean',
        'is_whole_day' => 'boolean',
        'date_start' => 'date',
        'date_end' => 'date',
        'time_start' => 'string',
        'time_end' => 'string'
    ];

    /**
     * Get all archived versions of this announcement
     */
    public function archives()
    {
        return $this->hasMany(AnnouncementArchive::class, 'announcement_id');
    }

    /**
     * Get the latest archive
     */
    public function latestArchive()
    {
        return $this->hasOne(AnnouncementArchive::class, 'announcement_id')->latest('archived_at');
    }

    // Get active announcements
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get formatted date range
     */
    public function getFormattedDateRangeAttribute()
    {
        if (!$this->date_start) {
            return null;
        }

        $dateStart = $this->date_start->format('F j, Y');
        
        if ($this->date_end && $this->date_end != $this->date_start) {
            $dateEnd = $this->date_end->format('F j, Y');
            
            // Check if same month and year
            if ($this->date_start->format('F Y') === $this->date_end->format('F Y')) {
                return $this->date_start->format('F j') . ' - ' . $this->date_end->format('j, Y');
            }
            
            return $dateStart . ' - ' . $dateEnd;
        }
        
        return $dateStart;
    }

    /**
     * Get formatted time range
     */
    public function getFormattedTimeRangeAttribute()
    {
        if ($this->is_whole_day) {
            return 'Whole Day';
        }

        if ($this->time_start && $this->time_end) {
            try {
                $timeStart = \Carbon\Carbon::parse($this->time_start)->format('g:i A');
                $timeEnd = \Carbon\Carbon::parse($this->time_end)->format('g:i A');
                return $timeStart . ' - ' . $timeEnd;
            } catch (\Exception $e) {
                return $this->time_start . ' - ' . $this->time_end;
            }
        }

        if ($this->time_start) {
            try {
                return \Carbon\Carbon::parse($this->time_start)->format('g:i A');
            } catch (\Exception $e) {
                return $this->time_start;
            }
        }

        return null;
    }
}
