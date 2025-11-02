<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnnouncementArchive extends Model
{
    protected $fillable = [
        'announcement_id',
        'title',
        'subheading',
        'content',
        'image_path',
        'ticker_text',
        'show_ticker',
        'is_active',
        'archived_by',
        'archived_at',
        'date_start',
        'date_end',
        'time_start',
        'time_end',
        'is_whole_day',
    ];

    protected $casts = [
        'show_ticker' => 'boolean',
        'is_active' => 'boolean',
        'is_whole_day' => 'boolean',
        'archived_at' => 'datetime',
        'date_start' => 'date',
        'date_end' => 'date',
        'time_start' => 'string',
        'time_end' => 'string',
    ];

    /**
     * Get the announcement that was archived
     */
    public function announcement()
    {
        return $this->belongsTo(Announcement::class);
    }

    /**
     * Get the user who archived this announcement
     */
    public function archivedBy()
    {
        return $this->belongsTo(User::class, 'archived_by');
    }

    /**
     * Create archive from announcement
     */
    public static function createFromAnnouncement(Announcement $announcement, $userId = null)
    {
        return self::create([
            'announcement_id' => $announcement->id,
            'title' => $announcement->title,
            'subheading' => $announcement->subheading,
            'content' => $announcement->content,
            'image_path' => $announcement->image_path,
            'ticker_text' => $announcement->ticker_text,
            'show_ticker' => $announcement->show_ticker,
            'is_active' => $announcement->is_active,
            'date_start' => $announcement->date_start,
            'date_end' => $announcement->date_end,
            'time_start' => $announcement->time_start,
            'time_end' => $announcement->time_end,
            'is_whole_day' => $announcement->is_whole_day,
            'archived_by' => $userId ?? auth()->id(),
            'archived_at' => now(),
        ]);
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
