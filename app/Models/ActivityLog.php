<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'module',
        'description',
        'record_id',
        'record_type',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the user who performed the action
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Static method to log activity
     */
    public static function log($action, $module, $description, $recordId = null, $recordType = null, $oldValues = null, $newValues = null, $userId = null)
    {
        // Use provided user_id, or fall back to auth()->id(), or use user_id from newValues if it's an array
        $finalUserId = $userId;
        if ($finalUserId === null) {
            $finalUserId = auth()->id();
        }
        // If newValues is an array and contains user_id, use it
        if ($finalUserId === null && is_array($newValues) && isset($newValues['user_id'])) {
            $finalUserId = $newValues['user_id'];
        }
        // If newValues is an array and contains guard with user info, extract user_id
        if ($finalUserId === null && is_array($newValues) && isset($newValues['guard'])) {
            // Try to get user_id from auth if available
            $finalUserId = auth()->id();
        }

        return self::create([
            'user_id' => $finalUserId,
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'record_id' => $recordId,
            'record_type' => $recordType,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }
}
