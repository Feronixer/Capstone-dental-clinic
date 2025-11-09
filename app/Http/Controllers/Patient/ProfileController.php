<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserInfo;
use App\Models\ChatbotSetting;
use App\Models\ChatbotFaq;
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

        $chatbotSetting = ChatbotSetting::first() ?? ChatbotSetting::create([
            'enabled' => true,
            'welcome_message' => '',
            'quick_intents' => [],
        ]);
        $chatbotFaqs = ChatbotFaq::where('is_active', true)->orderBy('order')->get(['question', 'answer']);

        return view("patient.profile", compact('user', 'userInfo', 'chatbotSetting', 'chatbotFaqs'));
    }

    /**
     * Update the patient's profile information.
     */
    public function update(Request $request)
    {
        try {
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

            // Validate the incoming data
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'last_name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'email' => 'required|email|max:255|unique:users,email,' . $user->id,
                // Birthday and gender are read-only and come from user management
            ]);

            // Get existing user info to preserve birthday and gender from user management
            $existingUserInfo = $user->info;

            // Recalculate age from existing birthday if it exists
            $age = null;
            if ($existingUserInfo && $existingUserInfo->birthday) {
                $birthday = Carbon::parse($existingUserInfo->birthday);
                $age = $birthday->age;
            }

            // Update the user's email in users table
            $user->update([
                'email' => $validated['email']
            ]);

            // Update or create the user's info - preserve birthday and gender from user management
            $userInfoData = [
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'],
                'last_name' => $validated['last_name'],
                'phone' => $validated['phone'],
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

            UserInfo::updateOrCreate(
                ['user_id' => $user->id],
                $userInfoData
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
