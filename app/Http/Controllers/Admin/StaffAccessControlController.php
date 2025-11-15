<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Events\StaffAccessControlUpdated;
use App\Models\StaffAccessControl;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class StaffAccessControlController extends Controller
{
    /**
     * Display the staff access control page.
     */
    public function index()
    {
        // Get all staff members (role_id = 2)
        $staffMembers = User::where('role_id', 2)
            ->with(['info', 'accessControl'])
            ->orderBy('name', 'asc')
            ->get();

        // Ensure each staff has an access control record with all fields defaulted to true
        foreach ($staffMembers as $staff) {
            if (!$staff->accessControl) {
                StaffAccessControl::create([
                    'staff_id' => $staff->id,
                ]);
            } else {
                // Update existing records to ensure new fields are set to true if null
                $accessControl = $staff->accessControl;
                $needsUpdate = false;
                
                if (is_null($accessControl->can_manage_announcements)) {
                    $accessControl->can_manage_announcements = true;
                    $needsUpdate = true;
                }
                if (is_null($accessControl->can_delete_archives)) {
                    $accessControl->can_delete_archives = true;
                    $needsUpdate = true;
                }
                if (is_null($accessControl->can_manage_mails)) {
                    $accessControl->can_manage_mails = true;
                    $needsUpdate = true;
                }
                
                if ($needsUpdate) {
                    $accessControl->save();
                }
            }
        }

        // Refresh to get access controls
        $staffMembers = $staffMembers->fresh(['accessControl']);

        return view('admin.staff-access-control', compact('staffMembers'));
    }

    /**
     * Verify admin password before allowing edits.
     */
    public function verifyPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string'
        ]);

        $admin = Auth::guard('admin')->user();
        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Incorrect password. Please try again.'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Password verified successfully.'
        ]);
    }

    /**
     * Update staff access control.
     */
    public function update(Request $request, $staffId)
    {
        // Verify password first
        $request->validate([
            'password' => 'required|string',
            'access_controls' => 'required|array'
        ]);

        $admin = Auth::guard('admin')->user();
        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Incorrect password. Please try again.'
            ], 403);
        }

        // Verify staff exists and is actually a staff member
        $staff = User::where('id', $staffId)
            ->where('role_id', 2)
            ->firstOrFail();

        try {
        // Update or create access control
        $accessControl = StaffAccessControl::updateOrCreate(
            ['staff_id' => $staffId],
            $request->access_controls
        );

        // Refresh the access control to get all fields
        $accessControl->refresh();

        // Broadcast the event
        $event = new StaffAccessControlUpdated($staffId, $accessControl, $admin->id);
        \App\Http\Controllers\BroadcastController::storeEvent('staff-access-control.updated', $event->broadcastWith());

        return response()->json([
            'success' => true,
            'message' => 'Staff access control updated successfully.',
            'access_control' => $accessControl
        ]);
        } catch (\Exception $e) {
            \Log::error('Error updating staff access control: ' . $e->getMessage(), [
                'staff_id' => $staffId,
                'access_controls' => $request->access_controls,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating access control: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get staff access control data.
     */
    public function show($staffId)
    {
        $staff = User::where('id', $staffId)
            ->where('role_id', 2)
            ->with(['info', 'accessControl'])
            ->firstOrFail();

        // Ensure access control exists with all fields defaulted to true
        if (!$staff->accessControl) {
            $accessControl = StaffAccessControl::create([
                'staff_id' => $staff->id,
            ]);
            $staff->load('accessControl');
        } else {
            // Update existing records to ensure new fields are set to true if null
            $accessControl = $staff->accessControl;
            $needsUpdate = false;
            
            if (is_null($accessControl->can_manage_announcements)) {
                $accessControl->can_manage_announcements = true;
                $needsUpdate = true;
            }
            if (is_null($accessControl->can_delete_archives)) {
                $accessControl->can_delete_archives = true;
                $needsUpdate = true;
            }
            if (is_null($accessControl->can_manage_mails)) {
                $accessControl->can_manage_mails = true;
                $needsUpdate = true;
            }
            
            if ($needsUpdate) {
                $accessControl->save();
                $staff->load('accessControl');
            }
        }

        return response()->json([
            'success' => true,
            'staff' => $staff,
            'access_control' => $staff->accessControl
        ]);
    }
}
