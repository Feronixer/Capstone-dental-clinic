<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\UserInfo;
use App\Models\PasswordResetToken;
use App\Mail\PasswordChangeVerificationMail;
use Carbon\Carbon;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $userInfo = $user->info;

        return view("staff.profile", compact('user', 'userInfo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Check if email is being changed
        $emailChanged = $request->email !== $user->email;

        // If email is being changed, require password verification
        if ($emailChanged) {
            $request->validate([
                'password' => 'required|string',
            ]);

            // Verify password
            if (!Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Incorrect password. Please enter your current password to change your email.'
                ], 422);
            }
        }

        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            // Birthday and gender are read-only and come from user management
        ]);

        // Update user table
        $user->update([
            'username' => $request->username,
            'email' => $request->email,
            'name' => trim($request->first_name . ' ' . $request->middle_name . ' ' . $request->last_name),
        ]);

        // Get existing user info to preserve birthday and gender from user management
        $existingUserInfo = $user->info;

        // Recalculate age from existing birthday if it exists
        $age = null;
        if ($existingUserInfo && $existingUserInfo->birthday) {
            $birthday = Carbon::parse($existingUserInfo->birthday);
            $age = $birthday->age;
        }

        // Update or create user_info - preserve birthday and gender from user management
        $userInfoData = [
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
        ];

        // Preserve birthday and gender from user management (only update age if birthday exists)
        if ($existingUserInfo) {
            if ($existingUserInfo->birthday) {
                $userInfoData['birthday'] = $existingUserInfo->birthday;
                $userInfoData['age'] = $age;
            }
            if ($existingUserInfo->gender) {
                $userInfoData['gender'] = $existingUserInfo->gender;
            }
        }

        // Add address if provided
        if ($request->filled('address')) {
            $userInfoData['address'] = $request->address;
        }

        UserInfo::updateOrCreate(
            ['user_id' => $user->id],
            $userInfoData
        );

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully!'
        ]);
    }

    /**
     * Update profile picture
     */
    public function updateProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            // Store new profile picture
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');

            $user->update([
                'profile_picture' => $path
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Profile picture updated successfully!',
                'profile_picture_url' => Storage::url($path)
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No file uploaded'
        ], 400);
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        // If send_code flag is set, send verification code to email
        if ($request->has('send_code') && $request->send_code) {
            try {
                // Create or update password reset token
                $passwordReset = PasswordResetToken::createOrUpdate($user->email);

                // Send email with verification code
                Mail::to($user->email)->send(new PasswordChangeVerificationMail($user, $passwordReset->token));

                return response()->json([
                    'success' => true,
                    'message' => 'Verification code has been sent to your email address.'
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to send password change verification email: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send verification code. Please try again.'
                ], 500);
            }
        }

        // If verify_code flag is set, verify the code
        if ($request->has('verify_code') && $request->verify_code) {
            $request->validate([
                'verification_code' => 'required|string|size:6',
            ]);

            $passwordReset = PasswordResetToken::verifyToken($user->email, $request->verification_code);

            if (!$passwordReset) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or expired verification code. Please request a new code.'
                ], 422);
            }

            // Store verification in session
            session(['password_change_verified' => true, 'password_change_verified_at' => now()]);

            return response()->json([
                'success' => true,
                'message' => 'Verification code verified successfully.'
            ]);
        }

        // Check if password change is verified
        if (!session('password_change_verified') || 
            now()->diffInMinutes(session('password_change_verified_at')) > 15) {
            return response()->json([
                'success' => false,
                'message' => 'Please verify your email first by entering the verification code.'
            ], 422);
        }

        $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        // Update password
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        // Clear verification session
        session()->forget(['password_change_verified', 'password_change_verified_at']);

        // Force logout across all guards
        Auth::guard('web')->logout();
        Auth::guard('staff')->logout();
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully! Please log in again.',
            'logout_redirect' => route('staff.login')
        ]);
    }
}
