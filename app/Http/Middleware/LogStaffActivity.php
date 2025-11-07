<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogStaffActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        try {
            // Only log mutating actions
            $method = strtoupper($request->method());
            if (!in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'])) {
                return $response;
            }

            // Only log for authenticated staff
            $staff = auth('staff')->user();
            if (!$staff) {
                return $response;
            }

            // Build safe metadata (avoid full body; remove passwords/tokens)
            $payload = $request->except(['password', 'confirm_password', 'admin_password', 'staff_password', '_token', '_method']);

            // Derive a compact action label from method
            $action = 'updated';
            if ($method === 'POST') {
                $action = 'created';
            } elseif ($method === 'DELETE') {
                $action = 'deleted';
            }

            // Entity hint from route name or URI
            $route = $request->route();
            $routeName = $route ? $route->getName() : null;
            $entity = $routeName ?: trim($request->path());

            // Human message
            $message = 'Staff ' . ($staff->username ?? ('ID#'.$staff->id)) . " $action " . $entity;

            // Use ActivityLog facade/class if available
            if (class_exists(\App\Models\ActivityLog::class)) {
                try {
                    // Create activity log with staff user ID
                    \App\Models\ActivityLog::create([
                        'user_id' => $staff->id,
                        'action' => $action,
                        'module' => 'staff',
                        'description' => $message,
                        'record_id' => null,
                        'record_type' => $entity,
                        'old_values' => null,
                        'new_values' => [
                            'route' => $entity,
                            'method' => $method,
                            'status' => $response->getStatusCode(),
                            'payload' => $payload
                        ],
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent()
                    ]);
                } catch (\Throwable $logError) {
                    // Silently fail logging
                }
            }
        } catch (\Throwable $e) {
            // Never break requests due to logging
        }

        return $response;
    }
}


