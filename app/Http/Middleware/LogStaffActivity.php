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

            // Human message defaults - simplified format
            $entityName = $this->simplifyEntityName($entity);
            $message = $this->formatSimpleDescription($displayName, $action, $entityName);
            $module = 'staff';
            $recordType = $entity;
            $recordId = null;

            // Try route-specific/custom messages using route name or path entity
            $custom = $this->buildCustomLogEntry($routeName ?: $entity, $payload, $staff, $action, $request);
            if ($custom) {
                $module = $custom['module'] ?? $module;
                $message = $custom['description'] ?? $message;
                $recordType = $custom['record_type'] ?? $recordType;
                if (array_key_exists('record_id', $custom)) {
                    $recordId = $custom['record_id'];
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

        // Handle un-named post-procedural routes using the request path signature
        $path = $routeName; // may be a route name or the raw URI path provided by caller
        if (is_string($path) && (str_starts_with($path, 'staff/post-procedural') || str_starts_with($path, 'admin/post-procedural'))) {
            // Progress Notes - bulk store
            if (str_contains($path, '/store-progress-notes') && $request->isMethod('post')) {
                return [
                    'module' => 'post_procedural',
                    'record_type' => 'progress_notes',
                    'description' => "Staff {$name} added progress notes",
                ];
            }
            // Progress Note - single create/update/delete
            if (str_contains($path, '/progress-notes') && $request->isMethod('post')) {
                return [
                    'module' => 'post_procedural',
                    'record_type' => 'progress_note',
                    'description' => "Staff {$name} added a progress note",
                ];
            }
            if (str_contains($path, '/progress-notes/') && $request->isMethod('put')) {
                $id = $request->route('id');
                return [
                    'module' => 'post_procedural',
                    'record_type' => 'progress_note',
                    'record_id' => $id,
                    'description' => "Staff {$name} updated progress note #{$id}",
                ];
            }
            if (str_contains($path, '/progress-notes/') && $request->isMethod('delete')) {
                $id = $request->route('id');
                return [
                    'module' => 'post_procedural',
                    'record_type' => 'progress_note',
                    'record_id' => $id,
                    'description' => "Staff {$name} deleted progress note #{$id}",
                ];
            }

            // Patient Record create/delete
            if (str_contains($path, '/patient-record/store') && $request->isMethod('post')) {
                return [
                    'module' => 'post_procedural',
                    'record_type' => 'patient_record',
                    'description' => "Staff {$name} created a post-procedural patient record",
                ];
            }
            if (preg_match('#/patient-record/(\d+)$#', $path) && $request->isMethod('delete')) {
                $id = $request->route('id');
                return [
                    'module' => 'post_procedural',
                    'record_type' => 'patient_record',
                    'record_id' => $id,
                    'description' => "Staff {$name} deleted patient record #{$id}",
                ];
            }

            // Patient History create/update/delete
            if (str_ends_with($path, '/patient-history') && $request->isMethod('post')) {
                return [
                    'module' => 'post_procedural',
                    'record_type' => 'patient_history',
                    'description' => "Staff {$name} added patient history",
                ];
            }
            if (preg_match('#/patient-history/(\d+)$#', $path) && $request->isMethod('put')) {
                $id = $request->route('id');
                return [
                    'module' => 'post_procedural',
                    'record_type' => 'patient_history',
                    'record_id' => $id,
                    'description' => "Staff {$name} updated patient history #{$id}",
                ];
            }
            if (preg_match('#/patient-history/(\d+)$#', $path) && $request->isMethod('delete')) {
                $id = $request->route('id');
                return [
                    'module' => 'post_procedural',
                    'record_type' => 'patient_history',
                    'record_id' => $id,
                    'description' => "Staff {$name} deleted patient history #{$id}",
                ];
            }
        }

        // Handle verify-password routes
        if (str_contains($routeName, 'verify-password') || str_contains($routeName, 'verifyPassword')) {
            return [
                'module' => 'security',
                'record_type' => 'password_verification',
                'description' => "Staff {$name} verified password",
            ];
        }

        return null;
    }

    /**
     * Simplify entity name for user-friendly descriptions
     */
    protected function simplifyEntityName(string $entity): string
    {
        // Remove common prefixes
        $entity = str_replace(['staff.', 'admin.', 'patient.'], '', $entity);
        
        // Convert route names to readable format
        $entity = str_replace(['-', '_'], ' ', $entity);
        
        // Handle common patterns
        $patterns = [
            '/verify-inactivity-password/' => 'password verification',
            '/verify-password/' => 'password verification',
            '/verifyPassword/' => 'password verification',
            '/post-procedural/' => 'post-procedural record',
            '/patient-record/' => 'patient record',
            '/patient-history/' => 'patient history',
            '/progress-notes/' => 'progress notes',
            '/progress-note/' => 'progress note',
            '/appointment/' => 'appointment',
            '/account-management/' => 'account',
            '/staff-access-control/' => 'staff access',
            '/toothtalk/' => 'ToothTalk',
            '/content-management/' => 'content',
            '/announcement/' => 'announcement',
            '/service/' => 'service',
            '/mail-template/' => 'mail template',
            '/blocked-time/' => 'blocked time',
            '/chat/' => 'chat',
        ];
        
        foreach ($patterns as $pattern => $replacement) {
            if (str_contains($entity, $pattern)) {
                $entity = str_replace($pattern, $replacement . ' ', $entity);
            }
        }
        
        // Capitalize first letter
        $entity = ucfirst(trim($entity));
        
        // If still looks like a route name, extract the last meaningful part
        if (str_contains($entity, '/')) {
            $parts = explode('/', $entity);
            $entity = end($parts);
            $entity = str_replace(['-', '_'], ' ', $entity);
            $entity = ucwords($entity);
        }
        
        return $entity ?: 'item';
    }

    /**
     * Format simple, user-friendly description
     */
    protected function formatSimpleDescription(string $name, string $action, string $entityName): string
    {
        $actionMap = [
            'created' => 'created',
            'updated' => 'updated',
            'deleted' => 'deleted',
            'verified' => 'verified',
        ];
        
        $actionText = $actionMap[$action] ?? $action;
        
        return "Staff {$name} {$actionText} {$entityName}";
    }

}


