<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserInfo;
use Carbon\Carbon;

class ProfileController extends Controller
{
    /**
     * Display the patient profile page with their information.
     */
    public function index()
    {
        $user = Auth::user();
        $userInfo = $user->info;

        return view("patient.profile", compact('user', 'userInfo'));
    }

    /**
     * Update the patient's profile information.
     */
    public function update(Request $request)
    {
        try {
            $user = Auth::user();

            // Validate the incoming data
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'last_name' => 'required|string|max:255',
                'birthday' => 'required|date',
                'gender' => 'required|in:Male,Female,Other',
                'phone' => 'required|string|max:20',
                'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            ]);

            // Calculate age from birthday
            $birthday = Carbon::parse($validated['birthday']);
            $age = $birthday->age;

            // Update the user's email in users table
            $user->update([
                'email' => $validated['email']
            ]);

            // Update or create the user's info
            UserInfo::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'first_name' => $validated['first_name'],
                    'middle_name' => $validated['middle_name'],
                    'last_name' => $validated['last_name'],
                    'birthday' => $validated['birthday'],
                    'age' => $age,
                    'gender' => $validated['gender'],
                    'phone' => $validated['phone'],
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully!'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Profile update error:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error updating profile: ' . $e->getMessage()
            ], 500);
        }
    }
}
