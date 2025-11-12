<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Display activity logs
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('user.info')
            ->whereHas('user', function($q) {
                // Only show logs from staff members (role_id = 2)
                $q->where('role_id', 2);
            })
            ->orderBy('created_at', 'desc');

        // Filter by staff member
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by module
        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search in description, module, and staff name
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('description', 'like', '%' . $searchTerm . '%')
                  ->orWhere('module', 'like', '%' . $searchTerm . '%')
                  ->orWhere('action', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('user.info', function($userQuery) use ($searchTerm) {
                      $userQuery->where('first_name', 'like', '%' . $searchTerm . '%')
                                ->orWhere('last_name', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        $logs = $query->paginate(20);

        // Get all staff members for filter dropdown
        $staffMembers = User::where('role_id', 2)->with('info')->get();

        return view('admin.activity-logs', compact('logs', 'staffMembers'));
    }

    /**
     * Get activity logs as JSON (for AJAX)
     */
    public function getLogs(Request $request)
    {
        $query = ActivityLog::with('user.info')
            ->whereHas('user', function($q) {
                $q->where('role_id', 2);
            })
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        // Search in description, module, action, and staff name
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('description', 'like', '%' . $searchTerm . '%')
                  ->orWhere('module', 'like', '%' . $searchTerm . '%')
                  ->orWhere('action', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('user.info', function($userQuery) use ($searchTerm) {
                      $userQuery->where('first_name', 'like', '%' . $searchTerm . '%')
                                ->orWhere('last_name', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        $logs = $query->paginate(20);

        return response()->json([
            'success' => true,
            'logs' => $logs
        ]);
    }

    /**
     * View detailed log information
     */
    public function show($id)
    {
        $log = ActivityLog::with('user.info')->findOrFail($id);

        return response()->json([
            'success' => true,
            'log' => $log
        ]);
    }
}
