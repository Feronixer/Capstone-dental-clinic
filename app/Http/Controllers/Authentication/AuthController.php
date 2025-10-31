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
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function showLoginForm(){
        // Check if user is already authenticated as a PATIENT
        if (Auth::check()) {
            $user = Auth::user();

            // Only redirect if they're trying to access THEIR OWN login page
            if ($user->role_id === 3) {
                // Patient trying to access patient login - redirect to dashboard
                return redirect()->route('patient-home')->with('info', 'You are already logged in.');
            }
            // If they're admin/staff trying to access patient login, allow it (they might want to switch accounts)
        }

        return view("auth.login");
    }

    public function login(Request $request) {
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
                'error' => 'The provided credentials do not match our records.',
            ])->withInput($request->only('email_username'));
        }

        // Verify password manually and then authenticate
        if (Hash::check($request->password, $user->password)) {
            // Manually log in the user using the web guard
            Auth::login($user);
            $request->session()->regenerate();

            // Check if user must change password (first-time login)
            if ($user->must_change_password) {
                return redirect()->route('password.change')->with('info', 'Please change your password to continue.');
            }

            // Redirect based on role
            // role_id: 1 = Admin, 2 = Staff, 3 = Patient
            switch ($user->role_id) {
                case 1:
                    // Admin: System oversight, reporting, user management, chatbot logs
                    return redirect()->route('admin-dashboard')->with('success', 'Welcome Admin!');

                case 2:
                    // Staff: Patient loads, appointments monitoring, patient management
                    return redirect()->route('staff-dashboard')->with('success', 'Welcome Staff!');

                case 3:
                    // Patient: Personal appointments and records
                    return redirect()->route('patient-home')->with('success', 'Welcome!');

                default:
                    // Fallback for unknown roles
                    Auth::logout();
                    return back()->withErrors([
                        'error' => 'Invalid user role. Please contact administrator.',
                    ]);
            }
        }

        return back()->withErrors([
            'error' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Show change password form
     */
    public function showChangePasswordForm()
    {
        return view('auth.change-password');
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

        return redirect()->route('patient-home')->with('success', 'Password changed successfully!');
    }

    public function register(Request $request) {
        $credential = $request->validate([
            'name' => ['required','string'],
            'email'=> ['required', 'email', 'string'],
            'password'=> ['required','string', 'min:8'],
        ]);

        User::create([
            'name'=> $request->name,
            'email'=> $request->email,
            'password'=> Hash::make($request->password),
        ]);
        return redirect()->route('login')->with('success','Success');
    }
    public function showRegisterForm(){
        return view('admin.register');
    }

    public function logout(Request $request): RedirectResponse
    {
        // Logout from all guards to ensure complete session cleanup
        Auth::guard('web')->logout();
        Auth::guard('staff')->logout();
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Set session flag to notify other tabs via localStorage
        $request->session()->put('patient_logout_flag', time());

        // Redirect to patient login portal
        return redirect()->route('login')->with('success','Logged out successfully');
    }

    public function addUser(Request $request)
    {
        $credential = $request->validate([
            'username' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|string|same:password',
            'role_id' => 'required|exists:roles,id'
        ]);

        User::create([
            'username' => $credential['username'],
            'name' => $credential['name'],
            'email' => $credential['email'],
            'password' => bcrypt($credential['password']),
            'role_id' => $credential['role_id']
        ]);

        return redirect()->route('admin-account-management')->with('success', 'User added successfully.');
    }

    /**
     * Show forgot password form
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send password reset verification code
     */
    public function sendResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.exists' => 'The email address is not registered in our system.'
        ]);

        $user = User::where('email', $request->email)->first();

        // Create or update password reset token
        $passwordReset = PasswordResetToken::createOrUpdate($request->email);

        try {
            // Send email with verification code
            Mail::to($user->email)->send(new PasswordResetMail($user, $passwordReset->token));

            return redirect()->route('password.reset.verify', ['email' => $request->email])
                ->with('success', 'A verification code has been sent to your email address.');
        } catch (\Exception $e) {
            \Log::error('Failed to send password reset email: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to send verification code. Please try again.']);
        }
    }

    /**
     * Show password reset verification form
     */
    public function showResetVerifyForm(Request $request)
    {
        $email = $request->query('email');

        if (!$email) {
            return redirect()->route('password.forgot')->withErrors(['error' => 'Invalid request.']);
        }

        return view('auth.reset-verify', compact('email'));
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

        $passwordReset = PasswordResetToken::verifyToken($request->email, $request->verification_code);

        if (!$passwordReset) {
            return back()->withErrors(['verification_code' => 'Invalid or expired verification code.']);
        }

        // Store email in session for password reset
        session(['reset_email' => $request->email]);

        return redirect()->route('password.reset.form')
            ->with('success', 'Verification successful. You can now set your new password.');
    }

    /**
     * Show new password form
     */
    public function showResetForm()
    {
        if (!session('reset_email')) {
            return redirect()->route('password.forgot')->withErrors(['error' => 'Session expired. Please request a new verification code.']);
        }

        return view('auth.reset-password');
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request)
    {
        if (!session('reset_email')) {
            return redirect()->route('password.forgot')->withErrors(['error' => 'Session expired. Please request a new verification code.']);
        }

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required'
        ], [
            'password.confirmed' => 'Password confirmation does not match.',
            'password.min' => 'Password must be at least 8 characters long.'
        ]);

        $email = session('reset_email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('password.forgot')->withErrors(['error' => 'User not found.']);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Delete the used token
        PasswordResetToken::where('email', $email)->delete();

        // Clear session
        session()->forget('reset_email');

        return redirect()->route('login')->with('success', 'Password reset successfully. You can now login with your new password.');
    }
}
