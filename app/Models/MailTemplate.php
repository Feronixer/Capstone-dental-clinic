<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MailTemplate extends Model
{
    protected $fillable = [
        'type',
        'subject',
        'content'
    ];

    // Available template types
    const TYPE_INITIAL_CONFIRMATION = 'initial_confirmation';
    const TYPE_REMINDER = 'reminder';
    const TYPE_CANCELLATION = 'cancellation';
    const TYPE_RESCHEDULING = 'rescheduling';
    const TYPE_FOLLOW_UP = 'follow_up';

    // Get template by type
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }
}
