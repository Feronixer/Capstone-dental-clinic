<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- needed

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // (Optional) Guests → login
        $middleware->redirectGuestsTo(fn (Request $r) => route('login'));

        // Users → role-based home (Admin/Staff/Patient)
        $middleware->redirectUsersTo(function (Request $r) {
            $user = Auth::user();
            if (!$user) {
                return route('login');
            }

            // Normalize role (supports roles.slug or roles.role; fallback to role_id)
            $rel  = optional($user->role);
            $slug = strtolower(trim($rel->slug ?? $rel->role ?? ''));
            if ($slug === '') {
                // Adjust IDs if yours differ
                $map = [1 => 'admin', 2 => 'staff', 3 => 'patient'];
                $slug = strtolower(trim($map[$user->role_id] ?? ''));
            }

            return match ($slug) {
                'admin'   => route('admin-dashboard'),
                'staff'   => route('staff-dashboard'),
                'patient' => route('patient-dashboard'),
                default   => route('login'),
            };
        });

        // Middleware aliases
        $middleware->alias([
            'role'                  => \App\Http\Middleware\EnsureRole::class,
            'force.change.password' => \App\Http\Middleware\ForceChangePassword::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
