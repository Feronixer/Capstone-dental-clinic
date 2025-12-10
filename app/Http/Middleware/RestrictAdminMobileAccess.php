<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictAdminMobileAccess
{
    /**
     * Allowed routes for mobile admin access
     */
    private $allowedRoutes = [
        'admin-notification',
        'admin-appointment',
        'admin-staff-access-control',
        'admin-content-management',
        'admin-announcement-archives',
        'admin-profile',
        'admin.logout',
        'admin.password.change',
        'admin.password.change.submit',
    ];

    /**
     * Allowed route patterns for mobile admin access
     */
    private $allowedRoutePatterns = [
        'admin-notification.*',
        'admin-appointment.*',
        'admin-staff-access-control.*',
        'admin-content-management.announcement.*',
        'admin-content-management.ticker.*',
        'admin-announcement-archives.*',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if admin is on mobile device
        $isMobile = session('is_mobile_device', false);
        
        if (!$isMobile) {
            // Not mobile, allow all access
            return $next($request);
        }

        // Mobile device detected - check if route is allowed
        $routeName = $request->route()?->getName();
        
        if ($routeName && $this->isRouteAllowed($routeName)) {
            return $next($request);
        }

        // Route not allowed on mobile - redirect to notifications with message
        return redirect()->route('admin-notification')
            ->with('error', 'This feature is not available on mobile devices. Please use a desktop computer to access this feature.');
    }

    /**
     * Check if a route is allowed for mobile access
     */
    private function isRouteAllowed(string $routeName): bool
    {
        // Check exact route matches
        if (in_array($routeName, $this->allowedRoutes)) {
            return true;
        }

        // Check route patterns
        foreach ($this->allowedRoutePatterns as $pattern) {
            if (fnmatch($pattern, $routeName)) {
                return true;
            }
        }

        return false;
    }
}

