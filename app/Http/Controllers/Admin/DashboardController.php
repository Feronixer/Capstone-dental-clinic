<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get current month and year for calendar
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Get total patients count (role_id = 3)
        $totalPatients = User::whereHas('info', function($query) {
            $query->where('role_id', 3);
        })->count();

        // Get today's appointments count
        $todayAppointments = Appointment::whereDate('start_datetime', Carbon::today())
            ->where('status', '!=', 'blocked')
            ->count();

        // Get total appointments count (all time)
        $totalAppointments = Appointment::where('status', '!=', 'blocked')->count();

        // Get staff members count (role_id = 1 for admin, 2 for staff)
        $staffMembers = User::whereHas('info', function($query) {
            $query->whereIn('role_id', [1, 2]);
        })->count();

        // Get today's appointments with details (only active appointments)
        $todayAppointmentsList = Appointment::whereDate('start_datetime', Carbon::today())
            ->whereIn('status', ['Pending', 'Confirmed', 'Completed'])
            ->with(['patient.info', 'service'])
            ->orderBy('start_datetime', 'asc')
            ->get();

        // Get upcoming appointments (next 7 days, excluding today, only active appointments)
        $upcomingAppointments = Appointment::whereDate('start_datetime', '>', Carbon::today())
            ->whereDate('start_datetime', '<=', Carbon::today()->addDays(7))
            ->whereIn('status', ['Pending', 'Confirmed'])
            ->with(['patient.info', 'service'])
            ->orderBy('start_datetime', 'asc')
            ->limit(5)
            ->get();

        // Get recent patients (last 5)
        $recentPatients = User::whereHas('info', function($query) {
            $query->where('role_id', 3);
        })
        ->with('info')
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

        // Get appointments for current year and adjacent years for calendar navigation
        $appointments = Appointment::whereBetween('start_datetime', [
                Carbon::create($currentYear - 1, 1, 1)->startOfDay(),
                Carbon::create($currentYear + 1, 12, 31)->endOfDay()
            ])
            ->where('status', '!=', 'blocked')
            ->with(['patient.info', 'service'])
            ->get()
            ->map(function($appointment) {
                // Format dates as 'Y-m-d H:i:s' string without timezone to avoid JS conversion
                $data = $appointment->toArray();
                $data['start_datetime'] = $appointment->start_datetime->format('Y-m-d H:i:s');
                $data['end_datetime'] = $appointment->end_datetime->format('Y-m-d H:i:s');
                return $data;
            });

        // Get blocked times for current year and adjacent years
        $blockedTimes = \App\Models\BlockedTime::whereBetween('start_datetime', [
                Carbon::create($currentYear - 1, 1, 1)->startOfDay(),
                Carbon::create($currentYear + 1, 12, 31)->endOfDay()
            ])
            ->get()
            ->map(function($blockedTime) {
                return [
                    'id' => $blockedTime->id,
                    'title' => $blockedTime->title,
                    'start_datetime' => $blockedTime->start_datetime->format('Y-m-d H:i:s'),
                    'end_datetime' => $blockedTime->end_datetime->format('Y-m-d H:i:s'),
                    'duration_minutes' => $blockedTime->duration_minutes,
                    'notes' => $blockedTime->notes,
                ];
            });

        // User Demographics - Gender distribution
        $maleCount = User::whereHas('info', function($query) {
            $query->where('role_id', 3)->where('gender', 'Male');
        })->count();

        $femaleCount = User::whereHas('info', function($query) {
            $query->where('role_id', 3)->where('gender', 'Female');
        })->count();

        // User Demographics - Age distribution
        $pediatricCount = User::whereHas('info', function($query) {
            $query->where('role_id', 3)
                  ->where('age', '<', 18)
                  ->whereNotNull('age');
        })->count();

        $adultCount = User::whereHas('info', function($query) {
            $query->where('role_id', 3)
                  ->where('age', '>=', 18)
                  ->whereNotNull('age');
        })->count();

        // Service Feedback - Get appointment counts by rating (1-5 stars)
        // Check if rating column exists, otherwise use sample data
        $feedbackData = [];
        try {
            for ($i = 5; $i >= 1; $i--) {
                $feedbackData[$i] = Appointment::where('rating', $i)
                    ->where('status', 'Completed')
                    ->count();
            }
        } catch (\Exception $e) {
            // Rating column doesn't exist yet, provide zero data
            for ($i = 5; $i >= 1; $i--) {
                $feedbackData[$i] = 0;
            }
        }

        // Most Performed Services - Get all services by appointment count
        $topServices = Appointment::where('status', '!=', 'blocked')
            ->whereNotNull('service_id')
            ->select('service_id', \DB::raw('count(*) as total'))
            ->groupBy('service_id')
            ->orderBy('total', 'desc')
            ->with('service')
            ->get()
            ->map(function($item) {
                return [
                    'name' => $item->service ? $item->service->service_name : 'Unknown Service',
                    'count' => $item->total
                ];
            });

        // Count appointments with "Other" service (where service_id is null but has reason_for_visit)
        $otherServiceCount = Appointment::where('status', '!=', 'blocked')
            ->whereNull('service_id')
            ->whereNotNull('reason_for_visit')
            ->count();

        // Add "Other" to the services list if there are any
        if ($otherServiceCount > 0) {
            $topServices->push([
                'name' => 'Other',
                'count' => $otherServiceCount
            ]);
        }

        // Sort by count descending and take top 5
        $topServices = $topServices->sortByDesc('count')->take(5)->values();

        // Get recent feedback (last 10 rated appointments)
        $recentFeedback = Appointment::whereNotNull('rating')
            ->where('status', 'Completed')
            ->with(['patient.info', 'service'])
            ->orderBy('rated_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function($appointment) {
                return [
                    'id' => $appointment->id,
                    'patient_name' => $appointment->patient && $appointment->patient->info
                        ? trim($appointment->patient->info->first_name . ' ' . $appointment->patient->info->last_name)
                        : ($appointment->patient ? $appointment->patient->name : 'Unknown'),
                    'service_name' => $appointment->service ? $appointment->service->service_name : 'Other',
                    'rating' => $appointment->rating,
                    'comment' => $appointment->patient_feedback,
                    'rated_at' => $appointment->rated_at
                        ? $appointment->rated_at->timezone('Asia/Manila')->format('M j, Y g:i A')
                        : ($appointment->updated_at ? $appointment->updated_at->timezone('Asia/Manila')->format('M j, Y g:i A') : 'N/A'),
                    'appointment_date' => $appointment->start_datetime->format('M j, Y'),
                ];
            });

        // Get pending appointments count
        $pendingAppointments = Appointment::whereDate('start_datetime', '>=', Carbon::today())
            ->where('status', 'Pending')
            ->count();

        return view('admin.dashboard', compact(
            'totalPatients',
            'todayAppointments',
            'totalAppointments',
            'pendingAppointments',
            'staffMembers',
            'todayAppointmentsList',
            'upcomingAppointments',
            'recentPatients',
            'appointments',
            'blockedTimes',
            'currentMonth',
            'currentYear',
            'maleCount',
            'femaleCount',
            'pediatricCount',
            'adultCount',
            'feedbackData',
            'topServices',
            'recentFeedback'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
