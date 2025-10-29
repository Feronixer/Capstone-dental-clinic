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
        return view('auth.admin-login');
    }

    /**
     * Handle admin login
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'min:8'],
        ]);

        // Try to find user by username
        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return back()->withErrors([
                'error' => 'Invalid admin credentials. Please check your username and password.',
            ])->withInput($request->only('username'));
        }

        // Check if user is admin (role_id = 1 ONLY)
        if ($user->role_id !== 1) {
            return back()->withErrors([
                'error' => 'Access denied. This portal is for administrators only.',
            ])->withInput($request->only('username'));
        }

        // Attempt login with username and password
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            $request->session()->regenerate();

            // Check if admin must change password (first-time login)
            if ($user->must_change_password) {
                \Log::info("Administrator '{$user->username}' requires password change");
                return redirect()->route('admin.password.change')->with('info', 'Please change your password to continue.');
            }

            // Log successful admin login
            \Log::info("Administrator '{$user->username}' logged in successfully");

            return redirect()->route('admin-dashboard')->with('success', 'Welcome back, Administrator!');
        }

        return back()->withErrors([
            'error' => 'Invalid credentials. Please try again.',
        ])->withInput($request->only('username'));
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
            'new_password' => ['required', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();

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
            'password' => ['required', 'string', 'min:8', 'confirmed'],
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
     * Logout admin
     */
    public function logout(Request $request)
    {
        $username = Auth::user()->username ?? 'unknown';

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        \Log::info("Administrator '{$username}' logged out");

        return redirect()->route('admin.login')
            ->with('success', 'You have been logged out successfully.');
    }
}

