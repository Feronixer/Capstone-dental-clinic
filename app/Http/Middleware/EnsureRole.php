<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Usage in routes: ->middleware('role:Admin') or 'role:Admin,Staff'
     * Works whether your Role model uses 'slug' ('patient') or 'role' ('Patient'),
     * and falls back to role_id mapping if the relation is missing.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        // 1) Normalize the allowed roles from route params (case-insensitive, trimmed)
        $allowed = array_map(
            fn ($r) => strtolower(trim($r)),
            $roles
        );

        // 2) Try to read role via relation: prefer slug, then name ("role")
        $fromRelation = optional($user->role);
        $current = strtolower(trim(
            ($fromRelation->slug ?? '') !== '' ? $fromRelation->slug
                                               : ($fromRelation->role ?? '')
        ));

        // 3) Fallback by role_id if relation/columns are missing
        if ($current === '') {
            // Adjust these IDs if yours differ
            $mapById = [
                1 => 'admin',
                2 => 'staff',
                3 => 'patient',
            ];
            $current = strtolower(trim($mapById[$user->role_id] ?? ''));
        }

        // 4) Final decision
        if ($current === '' || !in_array($current, $allowed, true)) {
            abort(403, 'Invalid Account.');
        }

        return $next($request);
    }
}
