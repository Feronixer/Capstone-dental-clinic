<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\UserInfo;
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
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect'
            ], 422);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully!'
        ]);
    }
}
