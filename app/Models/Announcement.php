<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'content',
        'image_path',
        'ticker_text',
        'show_ticker',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'show_ticker' => 'boolean'
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
}
