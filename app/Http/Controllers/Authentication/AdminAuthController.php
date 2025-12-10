<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PasswordResetToken;
use App\Mail\PasswordResetMail;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class AdminAuthController extends Controller
{
    /**
     * Show admin login form
     */
    public function showLoginForm()
    {
        // Check if user is already authenticated as an ADMIN
        if (Auth::guard('admin')->check()) {
            $isMobile = session('is_mobile_device', false);
            if ($isMobile) {
                return redirect()->route('admin-notification')->with('info', 'You are already logged in.');
            }
            return redirect()->route('admin-dashboard')->with('info', 'You are already logged in.');
        }

        return view('auth.admin-login');
    }

    /**
     * Handle admin login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email_username' => ['required', 'string'],
            'password' => ['required', 'min:8'],
        ]);

        // Try to find user by username or email
        $emailUsername = $request->email_username;
        $user = User::where(function($query) use ($emailUsername) {
                        $query->where('username', $emailUsername)
                              ->orWhere('email', $emailUsername);
                    })->first();

        if (!$user) {
            return back()->withErrors([
                'error' => 'Account does not exist. Please check your email/username.',
            ])->withInput($request->only('email_username'));
        }

        // Check if user is admin (role_id = 1 ONLY)
        if ($user->role_id !== 1) {
            return back()->withErrors([
                'error' => 'Access denied. This portal is for administrators only.',
            ])->withInput($request->only('email_username'));
        }

        // Verify password manually and then authenticate
        if (Hash::check($request->password, $user->password)) {
            // Detect mobile device
            $userAgent = $request->userAgent();
            $isMobile = $this->isMobileDevice($userAgent);
            session(['is_mobile_device' => $isMobile]);

            // Manually log in the user using the admin guard
            Auth::guard('admin')->login($user);
            $request->session()->regenerate();

            // Check if admin must change password (first-time login)
            if ($user->must_change_password) {
                \Log::info("Administrator '{$user->username}' requires password change");
                return redirect()->route('admin.password.change')->with('info', 'Please change your password to continue.');
            }

            // Log successful admin login
            $deviceType = $isMobile ? 'mobile' : 'desktop';
            \Log::info("Administrator '{$user->username}' logged in successfully from {$deviceType} device");

            if ($isMobile) {
                return redirect()->route('admin-notification')
                    ->with('success', 'Welcome back, Administrator! Note: Some features are limited on mobile devices.');
            }

            return redirect()->route('admin-dashboard')
                ->with('success', 'Welcome back, Administrator!');
        }

        return back()->withErrors([
            'error' => 'Wrong credentials. Please check your password.',
        ])->withInput($request->only('email_username'));
    }

    /**
     * Show change password form
     */
    public function showChangePasswordForm()
    {
        return view('auth.admin-change-password');
    }

    /**
     * Handle password change
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'new_password' => [
                'required',
                'min:8',
                'regex:/[a-z]/',      // At least one lowercase letter
                'regex:/[A-Z]/',      // At least one uppercase letter
                'regex:/[0-9]/',      // At least one number
                'regex:/[^A-Za-z0-9]/', // At least one special character
                'confirmed'
            ],
        ], [
            'new_password.required' => 'Please enter a new password.',
            'new_password.min' => 'Password must be at least 8 characters long.',
            'new_password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
            'new_password.confirmed' => 'Password confirmation does not match.',
        ]);

        $user = Auth::guard('admin')->user();

        // Verify user is admin
        if ($user->role_id !== 1) {
            return redirect()->route('admin.login')->withErrors([
                'error' => 'Unauthorized access.',
            ]);
        }

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'The current password is incorrect.',
            ]);
        }

        // Check if new password is the same as old password
        if (Hash::check($request->new_password, $user->password)) {
            return back()->withErrors([
                'new_password' => 'The new password must be different from your current password.',
            ]);
        }

        // Update password and reset must_change_password flag
        $user->password = Hash::make($request->new_password);
        $user->must_change_password = false;
        $user->save();

        \Log::info("Administrator '{$user->username}' changed password successfully");

        return redirect()->route('admin-dashboard')->with('success', 'Password changed successfully!');
    }

    /**
     * Show forgot password form
     */
    public function showForgotPasswordForm()
    {
        return view('auth.admin-forgot-password');
    }

    /**
     * Send password reset code
     */
    public function sendResetCode(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $user = User::where('email', $request->email)->first();

        // Verify user is admin
        if ($user->role_id !== 1) {
            return back()->withErrors([
                'error' => 'This email is not associated with an administrator account.',
            ])->withInput();
        }

        // Create or update password reset token
        $passwordReset = PasswordResetToken::createOrUpdate($request->email);

        // Send email with the code
        Mail::to($user->email)->send(new PasswordResetMail($user, $passwordReset->token));

        \Log::info("Password reset code sent to administrator: {$request->email}");

        return redirect()
            ->route('admin.password.reset.verify', ['email' => $request->email])
            ->with('success', 'Verification code sent to your email!');
    }

    /**
     * Show verification code form
     */
    public function showResetVerifyForm(Request $request)
    {
        $email = $request->query('email');

        if (!$email) {
            return redirect()->route('admin.password.forgot')
                ->withErrors(['error' => 'Email is required.']);
        }

        return view('auth.admin-reset-verify', ['email' => $email]);
    }

    /**
     * Verify reset code
     */
    public function verifyResetCode(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'verification_code' => ['required', 'string', 'size:6'],
        ]);

        $resetToken = PasswordResetToken::verifyToken($request->email, $request->verification_code);

        if (!$resetToken) {
            return back()->withErrors([
                'error' => 'Invalid or expired verification code. Please try again.',
            ])->withInput();
        }

        // Code is valid, redirect to reset password form
        return redirect()
            ->route('admin.password.reset.form', ['email' => $request->email, 'token' => $request->verification_code])
            ->with('success', 'Code verified! Please set your new password.');
    }

    /**
     * Show reset password form
     */
    public function showResetForm(Request $request)
    {
        $email = $request->query('email');
        $token = $request->query('token');

        if (!$email || !$token) {
            return redirect()->route('admin.password.forgot')
                ->withErrors(['error' => 'Invalid reset link.']);
        }

        // Verify token still exists and is valid
        $resetToken = PasswordResetToken::verifyToken($email, $token);

        if (!$resetToken) {
            return redirect()->route('admin.password.forgot')
                ->withErrors(['error' => 'Reset link has expired. Please try again.']);
        }

        return view('auth.admin-reset-password', [
            'email' => $email,
            'token' => $token
        ]);
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'token' => ['required', 'string'],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',      // At least one lowercase letter
                'regex:/[A-Z]/',      // At least one uppercase letter
                'regex:/[0-9]/',      // At least one number
                'regex:/[^A-Za-z0-9]/', // At least one special character
                'confirmed'
            ],
        ], [
            'password.required' => 'Please enter a new password.',
            'password.min' => 'Password must be at least 8 characters long.',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        // Verify token
        $resetToken = PasswordResetToken::verifyToken($request->email, $request->token);

        if (!$resetToken) {
            return back()->withErrors([
                'error' => 'Invalid or expired reset token.',
            ]);
        }

        // Update user password
        $user = User::where('email', $request->email)->first();

        if (!$user || $user->role_id !== 1) {
            return back()->withErrors([
                'error' => 'Administrator account not found.',
            ]);
        }

        // Check if new password is the same as old password
        if (Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'The new password must be different from your current password.',
            ]);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the reset token
        $resetToken->delete();

        \Log::info("Administrator password reset successfully: {$request->email}");

        return redirect()
            ->route('admin.login')
            ->with('success', 'Password reset successfully! Please log in with your new password.');
    }

    /**
     * Verify password for inactivity unlock
     */
    public function verifyInactivityPassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        $user = Auth::guard('admin')->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated.'
            ], 401);
        }

        // Verify password
        if (Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => true,
                'message' => 'Password verified successfully.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Incorrect password. Please try again.'
        ], 422);
    }

    /**
     * Logout admin
     */
    public function logout(Request $request)
    {
        $username = Auth::guard('admin')->user()->username ?? 'unknown';

        // Logout from all guards to ensure complete session cleanup
        Auth::guard('web')->logout();
        Auth::guard('staff')->logout();
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        \Log::info("Administrator '{$username}' logged out");

        // Set session flag to notify other tabs via localStorage
        $request->session()->put('admin_logout_flag', time());

        // Clear mobile device session flag
        $request->session()->forget('is_mobile_device');

        // Redirect to admin login portal
        return redirect()->route('admin.login')->with('success', 'You have been logged out successfully.');
    }

    /**
     * Check if the user agent indicates a mobile device
     */
    private function isMobileDevice(?string $userAgent): bool
    {
        if (empty($userAgent)) {
            return false;
        }

        // Common mobile device patterns
        $mobilePatterns = [
            'Mobile',
            'Android',
            'iPhone',
            'iPad',
            'iPod',
            'BlackBerry',
            'Windows Phone',
            'Opera Mini',
            'IEMobile',
            'Mobile Safari',
        ];

        foreach ($mobilePatterns as $pattern) {
            if (stripos($userAgent, $pattern) !== false) {
                return true;
            }
        }

        return false;
    }
}

