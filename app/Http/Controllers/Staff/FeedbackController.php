<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    use CheckStaffAccess;

    /**
     * Display all patient feedback.
     */
    public function index(Request $request)
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
        $accessCheck = $this->requireNavAccess('feedback');
        if ($accessCheck) {
            return $accessCheck;
        }

        // Get filter parameters
        $serviceFilter = $request->get('service');
        $ratingFilter = $request->get('rating');
        $monthFilter = $request->get('month');
        $sortFilter = $request->get('sort', 'newest'); // Default to newest

        // Get all services for filter dropdown
        $services = Service::orderBy('service_name')->get();

        // Build query for feedback
        $query = Appointment::whereNotNull('rating')
            ->where('status', 'Completed')
            ->with(['patient.info', 'service']);

        // Apply service filter
        if ($serviceFilter && $serviceFilter !== 'all') {
            if ($serviceFilter === 'other') {
                $query->whereNull('service_id');
            } else {
                $query->where('service_id', $serviceFilter);
            }
        }

        // Apply rating filter
        if ($ratingFilter && $ratingFilter !== 'all') {
            $query->where('rating', $ratingFilter);
        }

        // Apply month filter
        if ($monthFilter && $monthFilter !== 'all') {
            try {
                // Parse month filter (format: YYYY-MM)
                $monthParts = explode('-', $monthFilter);
                if (count($monthParts) === 2) {
                    $year = (int)$monthParts[0];
                    $month = (int)$monthParts[1];
                    $query->whereYear('rated_at', $year)
                          ->whereMonth('rated_at', $month);
                }
            } catch (\Exception $e) {
                // Invalid month filter, ignore it
            }
        }

        // Apply sort order
        $sortOrder = ($sortFilter === 'oldest') ? 'asc' : 'desc';
        $query->orderBy('rated_at', $sortOrder);

        // Get paginated results
        $allFeedback = $query->paginate(5)
            ->appends($request->query());

        return view('staff.feedback', compact('allFeedback', 'services', 'serviceFilter', 'ratingFilter', 'monthFilter', 'sortFilter'));
    }
}

