<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'service_name',
        'description',
        'price',
        'price_notes',
        'default_duration_minutes',
        'icon_class',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'default_duration_minutes' => 'integer'
    ];

    // Scope for active services
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
