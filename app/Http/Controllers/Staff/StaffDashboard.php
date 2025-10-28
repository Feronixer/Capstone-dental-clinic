<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StaffDashboard extends Controller
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

        // Get pending appointments count
        $pendingAppointments = Appointment::whereDate('start_datetime', '>=', Carbon::today())
            ->where('status', 'Pending')
            ->count();

        // Get today's appointments with details
        $todayAppointmentsList = Appointment::whereDate('start_datetime', Carbon::today())
            ->where('status', '!=', 'blocked')
            ->with(['patient.info', 'service'])
            ->orderBy('start_datetime', 'asc')
            ->get();

        // Get recent patients (last 6 for the card grid layout)
        $recentPatients = User::whereHas('info', function($query) {
            $query->where('role_id', 3);
        })
        ->with('info')
        ->orderBy('created_at', 'desc')
        ->limit(6)
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

        return view('staff.dashboard', compact(
            'totalPatients',
            'todayAppointments',
            'pendingAppointments',
            'todayAppointmentsList',
            'recentPatients',
            'appointments',
            'currentMonth',
            'currentYear'
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
