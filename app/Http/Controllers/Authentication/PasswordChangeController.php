<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as PasswordRule;

class PasswordChangeController extends Controller
{


    public function form()
    {
        return view('auth.password-change');
    }

    // Normalize role (supports roles.slug or roles.role; falls back to role_id)
    protected function resolveRoleSlug(User $user): string
    {
        $rel  = optional($user->role);
        $slug = $rel->slug ?? $rel->role ?? '';
        $slug = strtolower(trim($slug));
        if ($slug === '') {
            $map = [1 => 'admin', 2 => 'staff', 3 => 'patient']; // adjust if your IDs differ
            $slug = strtolower(trim($map[$user->role_id] ?? ''));
        }
        return $slug;
    }

    protected function homeRouteFor(User $user): string
    {
        return match ($this->resolveRoleSlug($user)) {
            'admin'   => 'admin-dashboard',
            'staff'   => 'staff-dashboard',
            'patient' => 'patient-dashboard',
            default   => 'login',
        };
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // Require current_password unless the user is flagged for forced change
        $rules = [
            'password' => ['required', 'string', PasswordRule::min(8), 'confirmed'],
        ];
        if (!$user->must_change_password) {
            $rules['current_password'] = ['required', 'current_password'];
        }

        $validated = $request->validate($rules);

        $user->forceFill([
            'password' => Hash::make($validated['password']),
            'must_change_password' => false,
        ])->save();

        // Send user to their role-based home
        return redirect()->route($this->homeRouteFor($user))
            ->with('status', 'Password updated successfully.');
    }
}
