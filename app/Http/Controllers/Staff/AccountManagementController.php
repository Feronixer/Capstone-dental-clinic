<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Models\UserInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AccountManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Staff can only manage patient accounts (role_id = 3)
        $users = $this->getFilteredUsers($request);
        $roles = Role::where('id', 3)->get(); // Only show Patient role

        if ($request->ajax()) {
            return response()->json([
                'html' => view('staff.partials.users-table', compact('users'))->render(),
                'pagination_html' => view('staff.partials.pagination-user-table', compact('users'))->render(),
            ]);
        }

        return view("staff.account-management.view", compact('users', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Staff can only create patient accounts (role_id = 3)
        $request->validate([
            'role_id' => ['required', 'integer', 'in:3'], // Only patients
            'username' => ['required','unique:users,username','max:255','regex:/^[A-Za-z0-9_-]+$/'],
            'first_name' => 'required|max:255',
            'middle_name' => 'nullable|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'phone' => ['required','regex:/^(09)\d{9}$/'],
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|string|same:password',
        ], [
            'phone.regex' => 'The phone number must start with 09 and should be 11 digits long.',
            'username.regex' => 'The username may only contain letters, numbers, underscores (_), and hyphens (-), and no spaces.',
            'confirm_password.same' => 'The confirm password and password must match.',
            'role_id.in' => 'Staff can only create patient accounts.',
        ]);

        $roleId = (int) $request->role_id;

        User::create([
            'role_id' => $roleId,
            'username' => $request->username,
            'name' => trim($request->first_name . ' ' . $request->middle_name . ' ' . $request->last_name),
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'created_at'=> Carbon::now(),
            'updated_at'=> Carbon::now(),
        ]);

        UserInfo::create([
            'user_id' => User::latest()->first()->id,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'created_at'=> Carbon::now(),
            'updated_at'=> Carbon::now(),
        ]);

        \Log::info('Staff created patient account', ['username' => $request->username, 'staff_user' => auth()->user()->username]);

        return redirect()->route('staff-account-management')->with('success', 'Patient account added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Staff can only view patient accounts
        $user = User::with('info', 'role')
            ->where('role_id', 3)
            ->findOrFail($id);
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Staff can only update patient accounts
        $user = User::with('info')
            ->where('role_id', 3)
            ->findOrFail($id);

        $request->validate([
            'role_id' => ['required', 'integer', 'in:3'], // Only patients
            'username' => ['required','max:255','regex:/^[A-Za-z0-9_-]+$/','unique:users,username,' . $user->id],
            'first_name' => 'required|max:255',
            'middle_name' => 'nullable|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => ['required','regex:/^(09)\d{9}$/'],
        ], [
            'phone.regex' => 'The phone number must start with 09 and should be 11 digits long.',
            'username.regex' => 'The username may only contain letters, numbers, underscores (_), and hyphens (-), and no spaces.',
            'role_id.in' => 'Staff can only manage patient accounts.',
        ]);

        $user->update([
            'role_id' => $request->role_id,
            'username' => $request->username,
            'name' => trim($request->first_name . ' ' . $request->middle_name . ' ' . $request->last_name),
            'email' => $request->email,
            'phone' => $request->phone,
            'updated_at' => now(),
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => bcrypt($request->password),
            ]);
        }

        if ($user->info) {
            $user->info->update([
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'updated_at' => now(),
            ]);
        } else {
            UserInfo::create([
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        \Log::info('Staff updated patient account', ['patient_id' => $id, 'staff_user' => auth()->user()->username]);

        return response()->json([
            'success' => true,
            'message' => 'Patient account updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage - RESTRICTED FOR STAFF
     */
    public function destroy(string $id)
    {
        // Staff CANNOT delete any accounts
        \Log::warning('Staff attempted to delete account', ['staff_user' => auth()->user()->username, 'target_user_id' => $id]);

        return response()->json([
            'status' => 'error',
            'message' => 'You do not have permission to delete accounts. Please contact an administrator.',
        ], 403);
    }

    private function applyRoleFilter($query, Request $request)
    {
        // Staff can only see patients (role_id = 3)
        $query->where('role_id', 3);

        return $query;
    }

    /**
     * Apply search filter if requested.
     */
    private function applySearchFilter($query, Request $request)
    {
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        return $query;
    }

    /**
     * Get filtered and paginated users (patients only).
     */
    private function getFilteredUsers(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $sort = $request->input('sort', 'id');
        $direction = $request->input('direction', 'asc');

        $query = User::query()->with(['role', 'info']);

        // Join roles table only if sorting/filtering by role
        if ($sort === 'role' || $request->filled('role')) {
            $query->leftJoin('roles', 'roles.id', '=', 'users.role_id')
                ->select('users.*'); // important
        }

        // Apply search & role filter (patients only)
        $query = $this->applyRoleFilter($query, $request);
        $query = $this->applySearchFilter($query, $request);

        // Order
        if ($sort === 'role') {
            $query->orderBy('roles.role', $direction);
        } else {
            $query->orderBy('users.' . $sort, $direction);
        }

        $users = $query->paginate($perPage)->appends($request->all());

        return $users;
    }

    public function changePasswword(Request $request, string $id)
    {
        // Staff can only change patient passwords
        $user = User::where('role_id', 3)->findOrFail($id);

        $request->validate([
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|string|same:password',
        ], [
            'confirm_password.same' => 'The confirm password and password must match.',
        ]);

        $user->update([
            'password' => bcrypt($request->password),
        ]);

        \Log::info('Staff changed patient password', ['patient_id' => $id, 'staff_user' => auth()->user()->username]);

        return response()->json([
            'status' => 'success',
            'message' => 'Patient password has been updated successfully.',
        ]);
    }
}

