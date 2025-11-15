<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ensure user is authenticated as admin (role_id = 1)
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login')->withErrors(['error' => 'Please login as administrator to access this page.']);
        }

        $user = Auth::guard('admin')->user();
        if ($user->role_id !== 1) {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login')->withErrors(['error' => 'Access denied. This portal is for administrators only.']);
        }

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

        // Get staff members count (role_id = 2 for staff only, excluding admins)
        $staffMembers = User::whereHas('info', function($query) {
            $query->where('role_id', 2);
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

        // User Demographics - Age distribution (calculated from birthday)
        // Get all patients with birthday and calculate age dynamically
        $patients = User::whereHas('info', function($query) {
            $query->where('role_id', 3)->whereNotNull('birthday');
        })->with('info')->get();

        $pediatricCount = 0;
        $adultCount = 0;

        foreach ($patients as $patient) {
            if ($patient->info && $patient->info->birthday) {
                $age = Carbon::parse($patient->info->birthday)->age;
                if ($age < 18) {
                    $pediatricCount++;
                } else {
                    $adultCount++;
                }
            }
        }

        // Service Feedback - Get appointment counts by rating (1-5 stars)
        // Only count completed appointments with actual ratings (not null)
        $feedbackData = [];
        for ($i = 5; $i >= 1; $i--) {
            $feedbackData[$i] = Appointment::where('status', 'Completed')
                ->where('rating', $i)
                ->whereNotNull('rating')
                ->count();
        }

        // Most Performed Services - Get all services by appointment count
        // Exclude blocked and cancelled appointments, only count active appointments
        $topServices = Appointment::whereNotIn('status', ['blocked', 'Cancelled'])
            ->whereNotNull('service_id')
            ->select('service_id', \DB::raw('count(*) as total'))
            ->groupBy('service_id')
            ->orderBy('total', 'desc')
            ->with('service')
            ->get()
            ->map(function($item) {
                return [
                    'name' => $item->service ? $item->service->service_name : 'Unknown Service',
                    'count' => (int)$item->total
                ];
            })
            ->filter(function($item) {
                return $item['name'] !== 'Unknown Service'; // Filter out invalid services
            });

        // Count appointments with "Other" service (where service_id is null but has reason_for_visit)
        $otherServiceCount = Appointment::whereNotIn('status', ['blocked', 'Cancelled'])
            ->whereNull('service_id')
            ->whereNotNull('reason_for_visit')
            ->count();

        // Add "Other" to the services list if there are any
        if ($otherServiceCount > 0) {
            $topServices->push([
                'name' => 'Other',
                'count' => (int)$otherServiceCount
            ]);
        }

        // Sort by count descending and take top 5, ensure count is integer
        $topServices = $topServices->sortByDesc('count')
            ->map(function($item) {
                $item['count'] = (int)$item['count'];
                return $item;
            })
            ->take(5)
            ->values();

        // Get recent feedback (last 3 rated appointments)
        $recentFeedback = Appointment::whereNotNull('rating')
            ->where('status', 'Completed')
            ->with(['patient.info', 'service'])
            ->orderBy('rated_at', 'desc')
            ->limit(3)
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

        // Get cancelled appointments count
        $cancelledAppointments = Appointment::where('status', 'Cancelled')->count();

        // Get current services list (limit to 5 for dashboard)
        $clinicServices = Service::orderBy('created_at', 'desc')->get();

        return view('admin.dashboard', compact(
            'totalPatients',
            'todayAppointments',
            'totalAppointments',
            'pendingAppointments',
            'cancelledAppointments',
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
            'recentFeedback',
            'clinicServices'
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

    /**
     * Display a full list of clinic services.
     */
    public function services()
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login')->withErrors(['error' => 'Please login as administrator to access this page.']);
        }

        $user = Auth::guard('admin')->user();
        if ($user->role_id !== 1) {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login')->withErrors(['error' => 'Access denied. This portal is for administrators only.']);
        }

        $clinicServices = Service::orderBy('created_at', 'desc')->paginate(15);

        return view('admin.services.index', compact('clinicServices'));
    }
}
