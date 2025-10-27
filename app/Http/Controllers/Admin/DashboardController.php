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

        // Get staff members count (role_id = 1 for admin, 2 for staff)
        $staffMembers = User::whereHas('info', function($query) {
            $query->whereIn('role_id', [1, 2]);
        })->count();

        // Get today's appointments with details
        $todayAppointmentsList = Appointment::whereDate('start_datetime', Carbon::today())
            ->where('status', '!=', 'blocked')
            ->with(['patient.info', 'service'])
            ->orderBy('start_datetime', 'asc')
            ->get();

        // Get recent patients (last 5)
        $recentPatients = User::whereHas('info', function($query) {
            $query->where('role_id', 3);
        })
        ->with('info')
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

        // Get appointments for the current month for calendar
        $appointments = Appointment::whereMonth('start_datetime', $currentMonth)
            ->whereYear('start_datetime', $currentYear)
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

        // Get blocked times for the current month
        $blockedTimes = \App\Models\BlockedTime::whereMonth('start_datetime', $currentMonth)
            ->whereYear('start_datetime', $currentYear)
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

        return view('admin.dashboard', compact(
            'totalPatients',
            'todayAppointments',
            'staffMembers',
            'todayAppointmentsList',
            'recentPatients',
            'appointments',
            'blockedTimes',
            'currentMonth',
            'currentYear',
            'maleCount',
            'femaleCount',
            'pediatricCount',
            'adultCount',
            'feedbackData'
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
