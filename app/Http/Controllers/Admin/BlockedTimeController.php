<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlockedTime;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Events\BlockedTimeUpdated;

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

            // Check for overlaps with existing appointments (exclude cancelled appointments)
            $overlappingAppointment = Appointment::where(function($query) use ($startDateTime, $endDateTime) {
                $query->where('start_datetime', '<', $endDateTime)
                      ->where('end_datetime', '>', $startDateTime);
            })
            ->where('status', '!=', 'Cancelled')
            ->first();

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

            // Broadcast blocked time updated event
            try {
                $blockedTimeData = [
                    'id' => $blockedTime->id,
                    'title' => $blockedTime->title,
                    'start_datetime' => $blockedTime->start_datetime->format('Y-m-d H:i:s'),
                    'end_datetime' => $blockedTime->end_datetime->format('Y-m-d H:i:s'),
                    'duration_minutes' => $blockedTime->duration_minutes,
                    'notes' => $blockedTime->notes,
                ];
                $event = new BlockedTimeUpdated($blockedTimeData, 'created');
                event($event);
                \App\Http\Controllers\BroadcastController::storeEvent('blocked-time.updated', $event->broadcastWith());
            } catch (\Exception $e) {
                \Log::error('Failed to broadcast blocked time created event:', ['error' => $e->getMessage()]);
            }

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

            // Check for overlaps with appointments (excluding current blocked time and cancelled appointments)
            $overlappingAppointment = Appointment::where(function($query) use ($startDateTime, $endDateTime) {
                $query->where('start_datetime', '<', $endDateTime)
                      ->where('end_datetime', '>', $startDateTime);
            })
            ->where('status', '!=', 'Cancelled')
            ->first();

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
    public function destroy(Request $request, $id)
    {
        try {
            // Clean up expired blocked times first
            $this->cleanupExpiredBlockedTimes();

            $blockedTime = BlockedTime::findOrFail($id);
            \Log::info('Found blocked time to delete:', ['id' => $id, 'blocked_time' => $blockedTime->toArray()]);

            // Store blocked time data before deletion for broadcasting
            $blockedTimeData = [
                'id' => $blockedTime->id,
                'title' => $blockedTime->title,
                'start_datetime' => $blockedTime->start_datetime->format('Y-m-d H:i:s'),
                'end_datetime' => $blockedTime->end_datetime->format('Y-m-d H:i:s'),
                'duration_minutes' => $blockedTime->duration_minutes,
                'notes' => $blockedTime->notes,
            ];

            $blockedTime->delete();
            \Log::info('Blocked time deleted successfully:', ['id' => $id]);

            // Broadcast blocked time deleted event
            try {
                $event = new BlockedTimeUpdated($blockedTimeData, 'deleted');
                event($event);
                \App\Http\Controllers\BroadcastController::storeEvent('blocked-time.updated', $event->broadcastWith());
            } catch (\Exception $e) {
                \Log::error('Failed to broadcast blocked time deleted event:', ['error' => $e->getMessage()]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Blocked time deleted successfully',
                'deleted_id' => $id
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('Blocked time not found:', ['id' => $id, 'error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Blocked time not found'
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Error deleting blocked time:', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
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
            $todayStart = Carbon::today('Asia/Manila')->startOfDay();

            // Get all blocked times and filter in PHP to handle timezone issues
            // This ensures we catch clinic closed days regardless of how they're stored
            $blockedTimes = BlockedTime::where('end_datetime', '>=', $todayStart)
                ->get();

            $count = 0;
            foreach ($blockedTimes as $bt) {
                // Convert to Asia/Manila timezone for comparison
                $start = Carbon::parse($bt->start_datetime)->setTimezone('Asia/Manila');
                $end = Carbon::parse($bt->end_datetime)->setTimezone('Asia/Manila');
                
                // Check if it's a full-day closure (same date, starts at 00:00, ends at 23:59)
                $isSameDate = $start->format('Y-m-d') === $end->format('Y-m-d');
                $startsAtMidnight = $start->format('H:i:s') === '00:00:00';
                $endsAt2359 = $end->format('H:i') === '23:59';
                $isFutureOrToday = $end->format('Y-m-d') >= $todayStart->format('Y-m-d');
                
                if ($isSameDate && $startsAtMidnight && $endsAt2359 && $isFutureOrToday) {
                    $count++;
                }
            }

            \Log::info('Future clinic closed count query', [
                'count' => $count,
                'now' => $now->format('Y-m-d H:i:s'),
                'today_start' => $todayStart->format('Y-m-d H:i:s'),
                'total_blocked_times_checked' => $blockedTimes->count()
            ]);

            return response()->json([
                'success' => true,
                'count' => $count
            ]);
        } catch (\Exception $e) {
            \Log::error('Error getting future clinic closed count:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error getting future clinic closed count: ' . $e->getMessage(),
                'count' => 0
            ], 500);
        }
    }

    /**
     * Get list of future clinic closed dates.
     */
    public function getFutureClinicClosedDates()
    {
        try {
            $now = Carbon::now('Asia/Manila');
            $todayStart = Carbon::today('Asia/Manila')->startOfDay();

            // Get all blocked times and filter in PHP to handle timezone issues
            $blockedTimes = BlockedTime::where('end_datetime', '>=', $todayStart)
                ->get();

            $dateMap = []; // Track dates and their IDs
            foreach ($blockedTimes as $bt) {
                // Convert to Asia/Manila timezone for comparison
                $start = Carbon::parse($bt->start_datetime)->setTimezone('Asia/Manila');
                $end = Carbon::parse($bt->end_datetime)->setTimezone('Asia/Manila');
                
                // Check if it's a full-day closure (same date, starts at 00:00, ends at 23:59)
                $isSameDate = $start->format('Y-m-d') === $end->format('Y-m-d');
                $startsAtMidnight = $start->format('H:i:s') === '00:00:00';
                $endsAt2359 = $end->format('H:i') === '23:59';
                $isFutureOrToday = $end->format('Y-m-d') >= $todayStart->format('Y-m-d');
                
                if ($isSameDate && $startsAtMidnight && $endsAt2359 && $isFutureOrToday) {
                    $dateKey = $start->format('Y-m-d');
                    if (!isset($dateMap[$dateKey])) {
                        $dateMap[$dateKey] = [
                            'date' => $dateKey,
                            'ids' => [],
                            'formatted' => $start->format('M d, Y')
                        ];
                    }
                    $dateMap[$dateKey]['ids'][] = $bt->id;
                }
            }

            // Convert map to array and sort
            $clinicClosedDates = array_values($dateMap);
            usort($clinicClosedDates, function($a, $b) {
                return strcmp($a['date'], $b['date']);
            });

            return response()->json([
                'success' => true,
                'dates' => $clinicClosedDates
            ]);
        } catch (\Exception $e) {
            \Log::error('Error getting future clinic closed dates:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error getting future clinic closed dates: ' . $e->getMessage(),
                'dates' => []
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
            $todayStart = Carbon::today('Asia/Manila')->startOfDay();

            // Get all blocked times and filter in PHP to handle timezone issues
            $blockedTimes = BlockedTime::where('end_datetime', '>=', $todayStart)
                ->get();

            $count = 0;
            foreach ($blockedTimes as $bt) {
                // Convert to Asia/Manila timezone for comparison
                $start = Carbon::parse($bt->start_datetime)->setTimezone('Asia/Manila');
                $end = Carbon::parse($bt->end_datetime)->setTimezone('Asia/Manila');
                
                // Check if it's NOT a full-day closure (partial block)
                $isSameDate = $start->format('Y-m-d') === $end->format('Y-m-d');
                $startsAtMidnight = $start->format('H:i:s') === '00:00:00';
                $endsAt2359 = $end->format('H:i') === '23:59';
                $isFutureOrToday = $end->format('Y-m-d') >= $todayStart->format('Y-m-d');
                
                // It's a partial block if:
                // - It's today or in the future AND
                // - (NOT same date OR NOT starts at midnight OR NOT ends at 23:59)
                $isPartialBlock = $isFutureOrToday && (!$isSameDate || !$startsAtMidnight || !$endsAt2359);
                
                if ($isPartialBlock) {
                    $count++;
                }
            }

            \Log::info('Future block off time count query', [
                'count' => $count,
                'now' => $now->format('Y-m-d H:i:s'),
                'today_start' => $todayStart->format('Y-m-d H:i:s'),
                'total_blocked_times_checked' => $blockedTimes->count()
            ]);

            return response()->json([
                'success' => true,
                'count' => $count
            ]);
        } catch (\Exception $e) {
            \Log::error('Error getting future block off time count:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
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
            $todayStart = Carbon::today('Asia/Manila')->startOfDay();

            // Clean up any expired blocked times first
            $this->cleanupExpiredBlockedTimes();

            // Get all blocked times and filter in PHP to handle timezone issues
            $allBlockedTimes = BlockedTime::where('end_datetime', '>=', $todayStart)->get();

            $blockedTimesToDelete = [];
            foreach ($allBlockedTimes as $bt) {
                // Convert to Asia/Manila timezone for comparison
                $start = Carbon::parse($bt->start_datetime)->setTimezone('Asia/Manila');
                $end = Carbon::parse($bt->end_datetime)->setTimezone('Asia/Manila');
                
                // Check if it's a full-day closure (same date, starts at 00:00, ends at 23:59)
                $isSameDate = $start->format('Y-m-d') === $end->format('Y-m-d');
                $startsAtMidnight = $start->format('H:i:s') === '00:00:00';
                $endsAt2359 = $end->format('H:i') === '23:59';
                $isFutureOrToday = $end->format('Y-m-d') >= $todayStart->format('Y-m-d');
                
                if ($isSameDate && $startsAtMidnight && $endsAt2359 && $isFutureOrToday) {
                    $blockedTimesToDelete[] = $bt;
                }
            }

            \Log::info('Clear clinic closed - Found blocked times', [
                'count' => count($blockedTimesToDelete),
                'now' => $now->format('Y-m-d H:i:s'),
                'ids' => array_map(function($bt) { return $bt->id; }, $blockedTimesToDelete)
            ]);

            $deletedCount = 0;

            foreach ($blockedTimesToDelete as $blockedTime) {
                \Log::info('Processing blocked time for deletion', [
                    'id' => $blockedTime->id,
                    'start' => $blockedTime->start_datetime->format('Y-m-d H:i:s'),
                    'end' => $blockedTime->end_datetime->format('Y-m-d H:i:s'),
                    'title' => $blockedTime->title
                ]);

                $blockedTimeData = [
                    'id' => $blockedTime->id,
                    'title' => $blockedTime->title,
                    'start_datetime' => $blockedTime->start_datetime->format('Y-m-d H:i:s'),
                    'end_datetime' => $blockedTime->end_datetime->format('Y-m-d H:i:s'),
                    'duration_minutes' => $blockedTime->duration_minutes,
                    'notes' => $blockedTime->notes,
                ];

                if ($blockedTime->delete()) {
                    $deletedCount++;

                    try {
                        $event = new BlockedTimeUpdated($blockedTimeData, 'deleted');
                        event($event);
                        \App\Http\Controllers\BroadcastController::storeEvent('blocked-time.updated', $event->broadcastWith());
                    } catch (\Exception $e) {
                        \Log::error('Failed to broadcast blocked time deleted event:', ['error' => $e->getMessage()]);
                    }
                } else {
                    \Log::warning('Failed to delete blocked time', ['id' => $blockedTime->id]);
                }
            }

            \Log::info("Cleared {$deletedCount} future clinic closed day(s)");

            return response()->json([
                'success' => true,
                'message' => "Successfully cleared {$deletedCount} future clinic closed day(s)",
                'deleted_count' => $deletedCount
            ]);
        } catch (\Exception $e) {
            \Log::error('Error clearing future clinic closed days:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error clearing future clinic closed days: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear specific clinic closed days by date.
     */
    public function clearSpecificClinicClosed(Request $request)
    {
        try {
            $request->validate([
                'dates' => 'required|array|min:1',
                'dates.*' => 'required|date_format:Y-m-d'
            ]);

            $dates = $request->dates;
            $todayStart = Carbon::today('Asia/Manila')->startOfDay();

            // Clean up any expired blocked times first
            $this->cleanupExpiredBlockedTimes();

            // Get all blocked times and filter for the specific dates
            $allBlockedTimes = BlockedTime::where('end_datetime', '>=', $todayStart)->get();

            $blockedTimesToDelete = [];
            foreach ($allBlockedTimes as $bt) {
                // Convert to Asia/Manila timezone for comparison
                $start = Carbon::parse($bt->start_datetime)->setTimezone('Asia/Manila');
                $end = Carbon::parse($bt->end_datetime)->setTimezone('Asia/Manila');
                
                // Check if it's a full-day closure (same date, starts at 00:00, ends at 23:59)
                $isSameDate = $start->format('Y-m-d') === $end->format('Y-m-d');
                $startsAtMidnight = $start->format('H:i:s') === '00:00:00';
                $endsAt2359 = $end->format('H:i') === '23:59';
                $dateKey = $start->format('Y-m-d');
                
                // Check if this date is in the list of dates to clear
                if ($isSameDate && $startsAtMidnight && $endsAt2359 && in_array($dateKey, $dates)) {
                    $blockedTimesToDelete[] = $bt;
                }
            }

            \Log::info('Clear specific clinic closed - Found blocked times', [
                'count' => count($blockedTimesToDelete),
                'requested_dates' => $dates,
                'ids' => array_map(function($bt) { return $bt->id; }, $blockedTimesToDelete)
            ]);

            $deletedCount = 0;

            foreach ($blockedTimesToDelete as $blockedTime) {
                \Log::info('Processing blocked time for deletion', [
                    'id' => $blockedTime->id,
                    'start' => $blockedTime->start_datetime->format('Y-m-d H:i:s'),
                    'end' => $blockedTime->end_datetime->format('Y-m-d H:i:s'),
                    'title' => $blockedTime->title
                ]);

                $blockedTimeData = [
                    'id' => $blockedTime->id,
                    'title' => $blockedTime->title,
                    'start_datetime' => $blockedTime->start_datetime->format('Y-m-d H:i:s'),
                    'end_datetime' => $blockedTime->end_datetime->format('Y-m-d H:i:s'),
                    'duration_minutes' => $blockedTime->duration_minutes,
                    'notes' => $blockedTime->notes,
                ];

                if ($blockedTime->delete()) {
                    $deletedCount++;

                    try {
                        $event = new BlockedTimeUpdated($blockedTimeData, 'deleted');
                        event($event);
                        \App\Http\Controllers\BroadcastController::storeEvent('blocked-time.updated', $event->broadcastWith());
                    } catch (\Exception $e) {
                        \Log::error('Failed to broadcast blocked time deleted event:', ['error' => $e->getMessage()]);
                    }
                } else {
                    \Log::warning('Failed to delete blocked time', ['id' => $blockedTime->id]);
                }
            }

            \Log::info("Cleared {$deletedCount} specific clinic closed day(s)");

            return response()->json([
                'success' => true,
                'message' => "Successfully cleared {$deletedCount} clinic closed day(s)",
                'deleted_count' => $deletedCount
            ]);
        } catch (\Exception $e) {
            \Log::error('Error clearing specific clinic closed days:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error clearing specific clinic closed days: ' . $e->getMessage()
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
            $todayStart = Carbon::today('Asia/Manila')->startOfDay();

            // Clean up any expired blocked times first
            $this->cleanupExpiredBlockedTimes();

            // Get all blocked times and filter in PHP to handle timezone issues
            $allBlockedTimes = BlockedTime::where('end_datetime', '>=', $todayStart)->get();

            $blockedTimesToDelete = [];
            foreach ($allBlockedTimes as $bt) {
                // Convert to Asia/Manila timezone for comparison
                $start = Carbon::parse($bt->start_datetime)->setTimezone('Asia/Manila');
                $end = Carbon::parse($bt->end_datetime)->setTimezone('Asia/Manila');
                
                // Check if it's NOT a full-day closure (partial block)
                $isSameDate = $start->format('Y-m-d') === $end->format('Y-m-d');
                $startsAtMidnight = $start->format('H:i:s') === '00:00:00';
                $endsAt2359 = $end->format('H:i') === '23:59';
                $isFutureOrToday = $end->format('Y-m-d') >= $todayStart->format('Y-m-d');
                
                // It's a partial block if:
                // - It's today or in the future AND
                // - (NOT same date OR NOT starts at midnight OR NOT ends at 23:59)
                $isPartialBlock = $isFutureOrToday && (!$isSameDate || !$startsAtMidnight || !$endsAt2359);
                
                if ($isPartialBlock) {
                    $blockedTimesToDelete[] = $bt;
                }
            }

            \Log::info('Clear block off time - Found blocked times', [
                'count' => count($blockedTimesToDelete),
                'now' => $now->format('Y-m-d H:i:s'),
                'ids' => array_map(function($bt) { return $bt->id; }, $blockedTimesToDelete)
            ]);

            $deletedCount = 0;

            foreach ($blockedTimesToDelete as $blockedTime) {
                \Log::info('Processing blocked time for deletion', [
                    'id' => $blockedTime->id,
                    'start' => $blockedTime->start_datetime->format('Y-m-d H:i:s'),
                    'end' => $blockedTime->end_datetime->format('Y-m-d H:i:s'),
                    'title' => $blockedTime->title
                ]);

                $blockedTimeData = [
                    'id' => $blockedTime->id,
                    'title' => $blockedTime->title,
                    'start_datetime' => $blockedTime->start_datetime->format('Y-m-d H:i:s'),
                    'end_datetime' => $blockedTime->end_datetime->format('Y-m-d H:i:s'),
                    'duration_minutes' => $blockedTime->duration_minutes,
                    'notes' => $blockedTime->notes,
                ];

                if ($blockedTime->delete()) {
                    $deletedCount++;

                    try {
                        $event = new BlockedTimeUpdated($blockedTimeData, 'deleted');
                        event($event);
                        \App\Http\Controllers\BroadcastController::storeEvent('blocked-time.updated', $event->broadcastWith());
                    } catch (\Exception $e) {
                        \Log::error('Failed to broadcast blocked time deleted event:', ['error' => $e->getMessage()]);
                    }
                } else {
                    \Log::warning('Failed to delete blocked time', ['id' => $blockedTime->id]);
                }
            }

            \Log::info("Cleared {$deletedCount} future block off time(s)");

            return response()->json([
                'success' => true,
                'message' => "Successfully cleared {$deletedCount} future block off time(s)",
                'deleted_count' => $deletedCount
            ]);
        } catch (\Exception $e) {
            \Log::error('Error clearing future block off times:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error clearing future block off times: ' . $e->getMessage()
            ], 500);
        }
    }
}
