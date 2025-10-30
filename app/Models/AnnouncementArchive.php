<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnnouncementArchive extends Model
{
    protected $fillable = [
        'announcement_id',
        'title',
        'content',
        'image_path',
        'ticker_text',
        'show_ticker',
        'is_active',
        'archived_by',
        'archived_at',
    ];

    protected $casts = [
        'show_ticker' => 'boolean',
        'is_active' => 'boolean',
        'archived_at' => 'datetime',
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
            'content' => $announcement->content,
            'image_path' => $announcement->image_path,
            'ticker_text' => $announcement->ticker_text,
            'show_ticker' => $announcement->show_ticker,
            'is_active' => $announcement->is_active,
            'archived_by' => $userId ?? auth()->id(),
            'archived_at' => now(),
        ]);
    }
}
