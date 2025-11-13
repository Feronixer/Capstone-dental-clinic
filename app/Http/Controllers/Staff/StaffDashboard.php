<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffDashboard extends Controller
{
    use CheckStaffAccess;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ensure user is authenticated as staff (role_id = 2)
        if (!Auth::guard('staff')->check()) {
            return redirect()->route('staff.login')->withErrors(['error' => 'Please login as staff to access this page.']);
        }

        $user = Auth::guard('staff')->user();
        if ($user->role_id !== 2) {
            Auth::guard('staff')->logout();
            return redirect()->route('staff.login')->withErrors(['error' => 'Access denied. This portal is for staff members only.']);
        }

        // Check access control
        $accessCheck = $this->requireNavAccess('dashboard');
        if ($accessCheck) {
            return $accessCheck;
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

        // Get pending appointments count
        $pendingAppointments = Appointment::whereDate('start_datetime', '>=', Carbon::today())
            ->where('status', 'Pending')
            ->count();

        // Get today's appointments with details (only active appointments)
        $todayAppointmentsList = Appointment::whereDate('start_datetime', Carbon::today())
            ->whereIn('status', ['Pending', 'Confirmed', 'Completed'])
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

        // Get upcoming appointments (next 7 days, excluding today, only active appointments)
        $upcomingAppointments = Appointment::whereDate('start_datetime', '>', Carbon::today())
            ->whereDate('start_datetime', '<=', Carbon::today()->addDays(7))
            ->whereIn('status', ['Pending', 'Confirmed'])
            ->with(['patient.info', 'service'])
            ->orderBy('start_datetime', 'asc')
            ->limit(5)
            ->get();

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

        // Get total appointments count (all time)
        $totalAppointments = Appointment::where('status', '!=', 'blocked')->count();

        // Get current services list (limit to 5 for dashboard)
        $clinicServices = Service::orderBy('created_at', 'desc')->get();

        return view('staff.dashboard', compact(
            'totalPatients',
            'todayAppointments',
            'totalAppointments',
            'pendingAppointments',
            'todayAppointmentsList',
            'recentPatients',
            'appointments',
            'currentMonth',
            'currentYear',
            'recentFeedback',
            'upcomingAppointments',
            'maleCount',
            'femaleCount',
            'pediatricCount',
            'adultCount',
            'feedbackData',
            'topServices',
            'clinicServices'
        ));
    }

    /**
     * Display a full list of clinic services for staff.
     */
    public function services()
    {
        if (!Auth::guard('staff')->check()) {
            return redirect()->route('staff.login')->withErrors(['error' => 'Please login as staff to access this page.']);
        }

        $user = Auth::guard('staff')->user();
        if ($user->role_id !== 2) {
            Auth::guard('staff')->logout();
            return redirect()->route('staff.login')->withErrors(['error' => 'Access denied. This portal is for staff members only.']);
        }

        $accessCheck = $this->requireNavAccess('dashboard');
        if ($accessCheck) {
            return $accessCheck;
        }

        $clinicServices = Service::orderBy('created_at', 'desc')->paginate(15);

        return view('staff.services.index', compact('clinicServices'));
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
