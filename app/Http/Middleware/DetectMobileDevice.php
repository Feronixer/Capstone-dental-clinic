<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DetectMobileDevice
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Detect mobile device using user agent
        $userAgent = $request->userAgent();
        $isMobile = $this->isMobileDevice($userAgent);
        
        // Store device type in session
        session(['is_mobile_device' => $isMobile]);
        
        return $next($request);
    }

    /**
     * Check if the user agent indicates a mobile device
     */
    private function isMobileDevice(?string $userAgent): bool
    {
        if (empty($userAgent)) {
            return false;
        }

        // Common mobile device patterns
        $mobilePatterns = [
            'Mobile',
            'Android',
            'iPhone',
            'iPad',
            'iPod',
            'BlackBerry',
            'Windows Phone',
            'Opera Mini',
            'IEMobile',
            'Mobile Safari',
        ];

        foreach ($mobilePatterns as $pattern) {
            if (stripos($userAgent, $pattern) !== false) {
                // Additional check: exclude iPad from mobile if needed
                // For now, we'll treat iPad as mobile too
                return true;
            }
        }

        return false;
    }
}

