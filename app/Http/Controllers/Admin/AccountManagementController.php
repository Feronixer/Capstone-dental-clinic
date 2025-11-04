<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Models\UserInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use function PHPUnit\Framework\isEmpty;

class AccountManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = $this->getFilteredUsers($request);
        // Get only the 3 valid roles: Admin, Staff, Patient
        $roles = Role::whereIn('role', ['Admin', 'Staff', 'Patient'])
            ->orderBy('id', 'asc')
            ->get()
            ->unique('role')
            ->values();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.partials.users-table', compact('users'))->render(),
                'pagination_html' => view('admin.partials.pagination-user-table', compact('users'))->render(),
            ]);
        }

        return view("admin.account-management.view", compact('users', 'roles'));
    }

    /**
     * Verify admin's password and reveal user's email
     */
    public function revealEmail(Request $request, string $id)
    {
        $request->validate([
            'password' => 'required|string'
        ]);

        $admin = Auth::guard('admin')->user();
        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Incorrect password.'
            ], 403);
        }

        $user = User::findOrFail($id);

        return response()->json([
            'success' => true,
            'email' => $user->email,
        ]);
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
        $request->validate([
            'role_id' => ['required', 'integer', 'exists:roles,id'],
            'username' => ['required','unique:users,username','max:255','regex:/^[A-Za-z0-9_-]+$/'],
            'first_name' => 'required|max:255',
            'middle_name' => 'nullable|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'phone' => ['required','regex:/^(09)\d{9}$/'],
            'gender' => 'required|in:Male,Female',
            'birthday' => 'required|date',
            'address' => 'nullable|string|max:255',
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|string|same:password',
        ], [
            'phone.regex' => 'The phone number must start with 09 and should be 11 digits long.',
            'username.regex' => 'The username may only contain letters, numbers, underscores (_), and hyphens (-), and no spaces.',
            'confirm_password.same' => 'The confirm password and password must match.',
        ]);

        $roleId = (int) $request->role_id;

        // Calculate age from birthday
        $birthday = Carbon::parse($request->birthday);
        $age = $birthday->age;

        $user = User::create([
            'role_id' => $roleId,
            'username' => $request->username,
            'name' => trim($request->first_name . ' ' . $request->middle_name . ' ' . $request->last_name),
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'created_at'=> Carbon::now(),
            'updated_at'=> Carbon::now(),
        ]);

        $userInfoData = [
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'birthday' => $request->birthday,
            'age' => $age,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        if ($request->filled('address')) {
            $userInfoData['address'] = $request->address;
        }

        UserInfo::create($userInfoData);

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'User added successfully.'
            ]);
        }

        return redirect()->route('admin-account-management')->with('success', 'User added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::with('info', 'role')->findOrFail($id);
        return response()->json($user);
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
        // Verify admin password
        $request->validate([
            'admin_password' => 'required|string'
        ]);
        $admin = Auth::guard('admin')->user();
        if (!$admin || !Hash::check($request->admin_password, $admin->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Incorrect password.'
            ], 403);
        }

        $user = User::with('info')->findOrFail($id);
        $request->validate([
            'role_id' => ['required', 'integer', 'exists:roles,id'],
            'username' => ['required','max:255','regex:/^[A-Za-z0-9_-]+$/','unique:users,username,' . $user->id],
            'first_name' => 'required|max:255',
            'middle_name' => 'nullable|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => ['required','regex:/^(09)\d{9}$/'],
            'gender' => 'required|in:Male,Female',
            'birthday' => 'required|date',
            'address' => 'nullable|string|max:255',
        ], [
            'phone.regex' => 'The phone number must start with 09 and should be 11 digits long.',
            'username.regex' => 'The username may only contain letters, numbers, underscores (_), and hyphens (-), and no spaces.',
        ]);

        // Calculate age from birthday
        $birthday = Carbon::parse($request->birthday);
        $age = $birthday->age;

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

        $userInfoData = [
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'birthday' => $request->birthday,
            'age' => $age,
        ];

        // Add address if provided
        if ($request->filled('address')) {
            $userInfoData['address'] = $request->address;
        }

        UserInfo::updateOrCreate(
            ['user_id' => $user->id],
            $userInfoData
        );
        return response()->json([
            'success' => true,
            'message' => 'User updated successfully.',
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $request->validate([
            'admin_password' => 'required|string'
        ]);
        $admin = Auth::guard('admin')->user();
        if (!$admin || !Hash::check($request->admin_password, $admin->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Incorrect password.'
            ], 403);
        }
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'User has been deleted successfully',
        ]);
    }

    private function applyRoleFilter($query, Request $request)
    {
        if ($request->filled('role')) {
            $query->where('role_id', $request->input('role'));
        }
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
     * Get filtered and paginated users.
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

        // Apply search & role filter
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

    public function changePasswword(Request $request, string $id) {
        $request->validate([
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|string|same:password',
        ], [
            'confirm_password.same' => 'The confirm password and password must match.',
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'password' => bcrypt($request->password),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Password has been updated successfully.',
        ]);
    }

}
