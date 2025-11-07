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
use App\Models\ActivityLog;

class StaffAuthController extends Controller
{
    /**
     * Show staff login form
     */
    public function showLoginForm()
    {
        // Check if user is already authenticated as STAFF
        if (Auth::guard('staff')->check()) {
            return redirect()->route('staff-dashboard')->with('info', 'You are already logged in.');
        }

        return view('auth.staff-login');
    }

    /**
     * Handle staff login
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
                'error' => 'Invalid staff credentials. Please check your email/username and password.',
            ])->withInput($request->only('email_username'));
        }

        // Check if user is staff (role_id = 2 ONLY)
        if ($user->role_id !== 2) {
            return back()->withErrors([
                'error' => 'Access denied. This portal is for staff members only.',
            ])->withInput($request->only('email_username'));
        }

        // Verify password manually and then authenticate
        if (Hash::check($request->password, $user->password)) {
            // Manually log in the user using the staff guard
            Auth::guard('staff')->login($user);
            $request->session()->regenerate();

            // Check if staff must change password (first-time login)
            if ($user->must_change_password) {
                \Log::info("Staff member '{$user->username}' requires password change");
                return redirect()->route('staff.password.change')->with('info', 'Please change your password to continue.');
            }

            // Log successful staff login
            \Log::info("Staff member '{$user->username}' logged in successfully");
            try {
                ActivityLog::log(
                    'login',
                    'auth',
                    "Staff '{$user->username}' logged in",
                    $user->id,
                    'User',
                    null,
                    ['guard' => 'staff'],
                    $user->id // Pass user_id explicitly to ensure it's set
                );
            } catch (\Exception $e) {
                \Log::warning('ActivityLog failed (staff login): ' . $e->getMessage());
            }

            return redirect()->route('staff-dashboard')->with('success', 'Welcome back, Staff!');
        }

        return back()->withErrors([
            'error' => 'Invalid staff credentials. Please check your email/username and password.',
        ])->withInput($request->only('email_username'));
    }

    /**
     * Show change password form
     */
    public function showChangePasswordForm()
    {
        return view('auth.staff-change-password');
    }

    /**
     * Handle password change
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'min:8', 'confirmed'],
        ]);

        $user = Auth::guard('staff')->user();

        // Verify user is staff
        if ($user->role_id !== 2) {
            return redirect()->route('staff.login')->withErrors([
                'error' => 'Unauthorized access.',
            ]);
        }

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'The current password is incorrect.',
            ]);
        }

        // Update password and reset must_change_password flag
        $user->password = Hash::make($request->new_password);
        $user->must_change_password = false;
        $user->save();

        \Log::info("Staff member '{$user->username}' changed password successfully");

        return redirect()->route('staff-dashboard')->with('success', 'Password changed successfully!');
    }

    /**
     * Show staff forgot password form
     */
    public function showForgotPasswordForm()
    {
        return view('auth.staff-forgot-password');
    }

    /**
     * Send password reset verification code to staff
     */
    public function sendResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        // Find user by email and verify they are staff (role_id = 2 only)
        $user = User::where('email', $request->email)
            ->where('role_id', 2)
            ->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'This email is not registered to a staff account.'
            ])->withInput();
        }

        // Create or update password reset token
        $passwordReset = PasswordResetToken::createOrUpdate($request->email);

        try {
            // Send email with verification code
            Mail::to($user->email)->send(new PasswordResetMail($user, $passwordReset->token));

            \Log::info("Password reset code sent to staff member: {$user->email}");

            return redirect()->route('staff.password.reset.verify', ['email' => $request->email])
                ->with('success', 'A verification code has been sent to your staff email address.');
        } catch (\Exception $e) {
            \Log::error('Failed to send staff password reset email: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to send verification code. Please try again.']);
        }
    }

    /**
     * Show staff password reset verification form
     */
    public function showResetVerifyForm(Request $request)
    {
        $email = $request->query('email');

        if (!$email) {
            return redirect()->route('staff.password.forgot')->withErrors(['error' => 'Invalid request.']);
        }

        // Verify this email belongs to a staff member (role_id = 2 only)
        $user = User::where('email', $email)
            ->where('role_id', 2)
            ->first();

        if (!$user) {
            return redirect()->route('staff.password.forgot')->withErrors(['error' => 'Invalid staff email.']);
        }

        return view('auth.staff-reset-verify', compact('email'));
    }

    /**
     * Verify reset code and show new password form
     */
    public function verifyResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'verification_code' => 'required|string|size:6'
        ]);

        // Verify this email belongs to a staff member
        $user = User::where('email', $request->email)
            ->whereIn('role_id', [1, 2])
            ->first();

        if (!$user) {
            return back()->withErrors(['error' => 'Invalid staff account.']);
        }

        $passwordReset = PasswordResetToken::verifyToken($request->email, $request->verification_code);

        if (!$passwordReset) {
            return back()->withErrors(['verification_code' => 'Invalid or expired verification code.'])->withInput();
        }

        // Store email in session for password reset
        session(['staff_reset_email' => $request->email]);

        \Log::info("Password reset code verified for staff member: {$request->email}");

        return redirect()->route('staff.password.reset.form')
            ->with('success', 'Verification successful. You can now set your new password.');
    }

    /**
     * Show new password form
     */
    public function showResetForm()
    {
        if (!session('staff_reset_email')) {
            return redirect()->route('staff.password.forgot')->withErrors(['error' => 'Session expired. Please request a new verification code.']);
        }

        return view('auth.staff-reset-password');
    }

    /**
     * Reset staff password
     */
    public function resetPassword(Request $request)
    {
        if (!session('staff_reset_email')) {
            return redirect()->route('staff.password.forgot')->withErrors(['error' => 'Session expired. Please request a new verification code.']);
        }

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required'
        ], [
            'password.confirmed' => 'Password confirmation does not match.',
            'password.min' => 'Password must be at least 8 characters long.'
        ]);

        $email = session('staff_reset_email');
        $user = User::where('email', $email)
            ->whereIn('role_id', [1, 2])
            ->first();

        if (!$user) {
            return redirect()->route('staff.password.forgot')->withErrors(['error' => 'Staff account not found.']);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Delete the used token
        PasswordResetToken::where('email', $email)->delete();

        // Clear session
        session()->forget('staff_reset_email');

        \Log::info("Password reset successfully for staff member: {$user->username}");

        return redirect()->route('staff.login')->with('success', 'Password reset successfully. You can now login with your new password.');
    }

    /**
     * Staff logout
     */
    public function logout(Request $request): RedirectResponse
    {
        // CRITICAL: Get user info BEFORE logging out
        $user = Auth::guard('staff')->user();
        $userId = $user ? $user->id : null;
        $username = $user ? $user->username : 'Unknown';

        // Logout from all guards to ensure complete session cleanup
        Auth::guard('web')->logout();
        Auth::guard('staff')->logout();
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        \Log::info("Staff member '$username' logged out");
        try {
            // Log with the captured user_id before logout
            if ($userId) {
                ActivityLog::log(
                    'logout',
                    'auth',
                    "Staff '{$username}' logged out",
                    $userId,
                    'User',
                    null,
                    ['guard' => 'staff'],
                    $userId // Pass user_id explicitly
                );
            }
        } catch (\Exception $e) {
            \Log::warning('ActivityLog failed (staff logout): ' . $e->getMessage());
        }

        // Set session flag to notify other tabs via localStorage
        $request->session()->put('staff_logout_flag', time());

        // Redirect to staff login portal
        return redirect()->route('staff.login')->with('success', 'Logged out successfully');
    }
}

