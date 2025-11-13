<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatCensoredWord extends Model
{
    use HasFactory;

    protected $fillable = [
        'word',
        'created_by_id',
        'created_by_type',
    ];
}

