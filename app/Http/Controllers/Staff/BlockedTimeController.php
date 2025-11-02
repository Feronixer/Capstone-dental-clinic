<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\BlockedTime;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BlockedTimeController extends Controller
{
    /**
     * Clean up expired blocked times (where end_datetime has passed)
     */
    protected function cleanupExpiredBlockedTimes()
    {
        $now = Carbon::now('Asia/Manila');
        $deleted = BlockedTime::where('end_datetime', '<', $now)->delete();

        if ($deleted > 0) {
            \Log::info("Cleaned up {$deleted} expired blocked time(s)");
        }

        return $deleted;
    }

    /**
     * Store a newly created blocked time.
     */
    public function store(Request $request)
    {
        try {
            // Clean up expired blocked times first
            $this->cleanupExpiredBlockedTimes();

            \Log::info('Blocked time creation request:', $request->all());

            $request->validate([
                'title' => 'required|string|max:255',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time',
                'description' => 'nullable|string',
            ]);

            // Parse dates in Asia/Manila timezone to avoid UTC conversion
            $startDateTime = Carbon::parse($request->start_time, 'Asia/Manila');
            $endDateTime = Carbon::parse($request->end_time, 'Asia/Manila');

            // Check for overlaps with existing appointments
            $overlappingAppointment = Appointment::where(function($query) use ($startDateTime, $endDateTime) {
                $query->where('start_datetime', '<', $endDateTime)
                      ->where('end_datetime', '>', $startDateTime);
            })->first();

            if ($overlappingAppointment) {
                return response()->json([
                    'success' => false,
                    'message' => 'This time slot conflicts with an existing appointment',
                    'errors' => ['start_time' => ['This time slot conflicts with an existing appointment']]
                ], 422);
            }

            // Check for overlaps with existing blocked times
            $overlappingBlockedTime = BlockedTime::where(function($query) use ($startDateTime, $endDateTime) {
                $query->where('start_datetime', '<', $endDateTime)
                      ->where('end_datetime', '>', $startDateTime);
            })->first();

            if ($overlappingBlockedTime) {
                return response()->json([
                    'success' => false,
                    'message' => 'This time slot conflicts with another blocked time',
                    'errors' => ['start_time' => ['This time slot conflicts with another blocked time']]
                ], 422);
            }

            // Calculate duration in minutes
            $durationMinutes = $startDateTime->diffInMinutes($endDateTime);

            // Create blocked time entry
            $blockedTime = BlockedTime::create([
                'title' => $request->title ?? 'Blocked Time',
                'start_datetime' => $startDateTime,
                'end_datetime' => $endDateTime,
                'duration_minutes' => $durationMinutes,
                'notes' => $request->description,
            ]);

            \Log::info('Blocked time created successfully:', ['id' => $blockedTime->id]);

            return response()->json([
                'success' => true,
                'message' => 'Time blocked successfully',
                'blocked_time' => $blockedTime
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error:', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Blocked time creation error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error creating blocked time: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified blocked time.
     */
    public function update(Request $request, string $id)
    {
        try {
            // Clean up expired blocked times first
            $this->cleanupExpiredBlockedTimes();

            $blockedTime = BlockedTime::findOrFail($id);

            $request->validate([
                'title' => 'required|string|max:255',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time',
                'description' => 'nullable|string',
            ]);

            // Parse dates in Asia/Manila timezone to avoid UTC conversion
            $startDateTime = Carbon::parse($request->start_time, 'Asia/Manila');
            $endDateTime = Carbon::parse($request->end_time, 'Asia/Manila');

            // Check for overlaps with appointments (excluding current blocked time)
            $overlappingAppointment = Appointment::where(function($query) use ($startDateTime, $endDateTime) {
                $query->where('start_datetime', '<', $endDateTime)
                      ->where('end_datetime', '>', $startDateTime);
            })->first();

            if ($overlappingAppointment) {
                return response()->json([
                    'success' => false,
                    'message' => 'This time slot conflicts with an existing appointment',
                    'errors' => ['start_time' => ['This time slot conflicts with an existing appointment']]
                ], 422);
            }

            // Check for overlaps with other blocked times (excluding current one)
            $overlappingBlockedTime = BlockedTime::where('id', '!=', $id)
                ->where(function($query) use ($startDateTime, $endDateTime) {
                    $query->where('start_datetime', '<', $endDateTime)
                          ->where('end_datetime', '>', $startDateTime);
                })->first();

            if ($overlappingBlockedTime) {
                return response()->json([
                    'success' => false,
                    'message' => 'This time slot conflicts with another blocked time',
                    'errors' => ['start_time' => ['This time slot conflicts with another blocked time']]
                ], 422);
            }

            // Calculate duration in minutes
            $durationMinutes = $startDateTime->diffInMinutes($endDateTime);

            // Update blocked time
            $blockedTime->update([
                'title' => $request->title ?? 'Blocked Time',
                'start_datetime' => $startDateTime,
                'end_datetime' => $endDateTime,
                'duration_minutes' => $durationMinutes,
                'notes' => $request->description,
            ]);

            \Log::info('Blocked time updated successfully:', ['id' => $blockedTime->id]);

            return response()->json([
                'success' => true,
                'message' => 'Blocked time updated successfully',
                'blocked_time' => $blockedTime
            ]);
        } catch (\Exception $e) {
            \Log::error('Error updating blocked time:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error updating blocked time: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified blocked time.
     */
    public function destroy(string $id)
    {
        try {
            // Clean up expired blocked times first
            $this->cleanupExpiredBlockedTimes();

            $blockedTime = BlockedTime::findOrFail($id);
            \Log::info('Found blocked time:', ['blocked_time' => $blockedTime]);

            $blockedTime->delete();
            \Log::info('Blocked time deleted successfully');

            return response()->json([
                'success' => true,
                'message' => 'Blocked time deleted successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error deleting blocked time:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error deleting blocked time: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get count of future full-day closures (Clinic Closed).
     */
    public function getFutureClinicClosedCount()
    {
        try {
            $now = Carbon::now('Asia/Manila');
            
            // Count only full-day closures (00:00 to 23:59)
            $count = BlockedTime::where('start_datetime', '>', $now)
                ->whereRaw('TIME(start_datetime) = ?', ['00:00:00'])
                ->whereRaw('TIME(end_datetime) = ?', ['23:59:00'])
                ->count();

            return response()->json([
                'success' => true,
                'count' => $count
            ]);
        } catch (\Exception $e) {
            \Log::error('Error getting future clinic closed count:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error getting future clinic closed count: ' . $e->getMessage(),
                'count' => 0
            ], 500);
        }
    }

    /**
     * Get count of future partial blocks (Block Off Time).
     */
    public function getFutureBlockOffTimeCount()
    {
        try {
            $now = Carbon::now('Asia/Manila');
            
            // Count partial blocks (NOT full-day closures)
            $count = BlockedTime::where('start_datetime', '>', $now)
                ->where(function($query) {
                    $query->whereRaw('TIME(start_datetime) != ?', ['00:00:00'])
                          ->orWhereRaw('TIME(end_datetime) != ?', ['23:59:00']);
                })
                ->count();

            return response()->json([
                'success' => true,
                'count' => $count
            ]);
        } catch (\Exception $e) {
            \Log::error('Error getting future block off time count:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error getting future block off time count: ' . $e->getMessage(),
                'count' => 0
            ], 500);
        }
    }

    /**
     * Clear all future full-day closures (Clinic Closed) only.
     */
    public function clearFutureClinicClosed()
    {
        try {
            $now = Carbon::now('Asia/Manila');
            
            // Delete only full-day closures (00:00 to 23:59) where start_datetime is in the future
            $deletedCount = BlockedTime::where('start_datetime', '>', $now)
                ->whereRaw('TIME(start_datetime) = ?', ['00:00:00'])
                ->whereRaw('TIME(end_datetime) = ?', ['23:59:00'])
                ->delete();

            \Log::info("Cleared {$deletedCount} future clinic closed day(s)");

            return response()->json([
                'success' => true,
                'message' => "Successfully cleared {$deletedCount} future clinic closed day(s)",
                'deleted_count' => $deletedCount
            ]);
        } catch (\Exception $e) {
            \Log::error('Error clearing future clinic closed days:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error clearing future clinic closed days: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear all future partial blocks (Block Off Time) only.
     */
    public function clearFutureBlockOffTime()
    {
        try {
            $now = Carbon::now('Asia/Manila');
            
            // Delete partial blocks (NOT full-day closures) where start_datetime is in the future
            $deletedCount = BlockedTime::where('start_datetime', '>', $now)
                ->where(function($query) {
                    $query->whereRaw('TIME(start_datetime) != ?', ['00:00:00'])
                          ->orWhereRaw('TIME(end_datetime) != ?', ['23:59:00']);
                })
                ->delete();

            \Log::info("Cleared {$deletedCount} future block off time(s)");

            return response()->json([
                'success' => true,
                'message' => "Successfully cleared {$deletedCount} future block off time(s)",
                'deleted_count' => $deletedCount
            ]);
        } catch (\Exception $e) {
            \Log::error('Error clearing future block off times:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error clearing future block off times: ' . $e->getMessage()
            ], 500);
        }
    }
}

