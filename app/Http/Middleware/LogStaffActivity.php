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

            $displayName = $this->formatStaffName($staff);

            // Human message defaults
            $message = 'Staff ' . $displayName . " $action " . $entity;
            $module = 'staff';
            $recordType = $entity;
            $recordId = null;

            if ($routeName) {
                $custom = $this->buildCustomLogEntry($routeName, $payload, $staff, $action, $request);
                if ($custom) {
                    $module = $custom['module'] ?? $module;
                    $message = $custom['description'] ?? $message;
                    $recordType = $custom['record_type'] ?? $recordType;
                    if (array_key_exists('record_id', $custom)) {
                        $recordId = $custom['record_id'];
                    }
                }
            }

            // Use ActivityLog facade/class if available
            if (class_exists(\App\Models\ActivityLog::class)) {
                try {
                    // Create activity log with staff user ID
                    \App\Models\ActivityLog::create([
                        'user_id' => $staff->id,
                        'action' => $action,
                        'module' => $module,
                        'description' => $message,
                        'record_id' => $recordId,
                        'record_type' => $recordType,
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

    /**
     * Normalize staff display name.
     */
    protected function formatStaffName($staff): string
    {
        if (!$staff) {
            return 'Unknown staff';
        }

        foreach (['full_name', 'name', 'username', 'email'] as $attribute) {
            if (!empty($staff->{$attribute})) {
                return $staff->{$attribute};
            }
        }

        return 'ID#' . $staff->id;
    }

    /**
     * Build route-specific activity log description and metadata.
     */
    protected function buildCustomLogEntry(?string $routeName, array $payload, $staff, string $action, Request $request): ?array
    {
        if (!$routeName) {
            return null;
        }

        $name = $this->formatStaffName($staff);

        switch ($routeName) {
            case 'staff-toothtalk.settings.save':
            case 'admin-toothtalk.settings.save':
                return [
                    'module' => 'toothtalk',
                    'record_type' => 'toothtalk_settings',
                    'description' => "Staff {$name} updated ToothTalk chatbot settings",
                ];

            case 'staff-toothtalk.faq.store':
            case 'admin-toothtalk.faq.store':
                return [
                    'module' => 'toothtalk',
                    'record_type' => 'toothtalk_faq',
                    'description' => "Staff {$name} added a ToothTalk FAQ",
                ];

            case 'staff-toothtalk.faq.delete':
            case 'admin-toothtalk.faq.delete':
                $faqId = $request->route('id');

                return [
                    'module' => 'toothtalk',
                    'record_type' => 'toothtalk_faq',
                    'record_id' => $faqId,
                    'description' => "Staff {$name} deleted ToothTalk FAQ #{$faqId}",
                ];

            case 'staff-toothtalk.faq.update':
            case 'admin-toothtalk.faq.update':
                $faqId = $request->route('id');

                return [
                    'module' => 'toothtalk',
                    'record_type' => 'toothtalk_faq',
                    'record_id' => $faqId,
                    'description' => "Staff {$name} updated ToothTalk FAQ #{$faqId}",
                ];
        }

        return null;
    }

}


