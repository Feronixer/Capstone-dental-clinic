<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatbotSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'enabled',
        'welcome_message',
        'quick_intents',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'quick_intents' => 'array',
    ];
}


