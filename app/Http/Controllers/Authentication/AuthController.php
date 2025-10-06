<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showLoginForm(){
        return view("auth.login");
    }

public function login(Request $request)
{
    $credentials = $request->validate([
        'email'    => ['required', 'email'],
        'password' => ['required', 'min:8'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        $user = Auth::user();

        // ✅ Force change password BEFORE any dashboard redirects
        if ($user->must_change_password) {
            $request->session()->forget('url.intended');
            return redirect()->route('password.change');
        }

        // Role-based redirect
        $home = $this->homeRouteFor($user);
        if (!$home) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return back()->withErrors(['error' => 'Your account has no valid role assigned.']);
        }

        $request->session()->forget('url.intended');
        return redirect()->route($home)->with('success','Login Successful');
    }

    return back()->withErrors([
        'error' => 'The provided credentials do not match our records.',
    ]);
}

/** Helpers (put in same controller) */
protected function resolveRoleSlug(User $user): string
{
    $rel  = optional($user->role);
    $slug = strtolower(trim($rel->slug ?? $rel->role ?? ''));
    if ($slug === '') {
        // adjust IDs if yours differ
        $map = [1 => 'admin', 2 => 'staff', 3 => 'patient'];
        $slug = strtolower(trim($map[$user->role_id] ?? ''));
    }
    return $slug;
}

protected function homeRouteFor(User $user): ?string
{
    return match ($this->resolveRoleSlug($user)) {
        'admin'   => 'admin-dashboard',
        'staff'   => 'staff-dashboard',
        'patient' => 'patient-dashboard',
        default   => null,
    };
}
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success','Logged out successfully');
    }

    public function addUser(Request $request)
    {
        $credential = $request->validate([
            'username' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|string|same:password',
            'role_id' => 'required|exists:roles,id'
        ]);

        User::create([
            'username' => $credential['username'],
            'name' => $credential['name'],
            'email' => $credential['email'],
            'password' => bcrypt($credential['password']),
            'role_id' => $credential['role_id']
        ]);

        return redirect()->route('admin-account-management')->with('success', 'User added successfully.');
    }
}
