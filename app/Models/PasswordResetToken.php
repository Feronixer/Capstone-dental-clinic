<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PasswordResetToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'token',
        'expires_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'created_at' => 'datetime'
    ];

    /**
     * Check if the token is expired
     */
    public function isExpired()
    {
        return $this->expires_at < Carbon::now();
    }

    /**
     * Generate a random 6-digit verification code
     */
    public static function generateToken()
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Create or update a password reset token
     */
    public static function createOrUpdate($email)
    {
        $token = self::generateToken();
        $expiresAt = Carbon::now()->addMinutes(15); // Token expires in 15 minutes

        return self::updateOrCreate(
            ['email' => $email],
            [
                'token' => $token,
                'expires_at' => $expiresAt
            ]
        );
    }

    /**
     * Verify the token
     */
    public static function verifyToken($email, $token)
    {
        $passwordReset = self::where('email', $email)
            ->where('token', $token)
            ->first();

        if (!$passwordReset || $passwordReset->isExpired()) {
            return false;
        }

        return $passwordReset;
    }
}
