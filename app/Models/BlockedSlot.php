<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockedSlot extends Model
{
    protected $fillable = [
        'date',
        'start_time',
        'end_time',
        'notes',
    ];
}
