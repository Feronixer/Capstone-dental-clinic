<?php

namespace App\Http\Controllers\Staff;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

trait CheckStaffAccess
{
    /**
     * Check if staff has access to a specific navigation item
     */
    protected function hasNavAccess($navItem)
    {
        $user = Auth::guard('staff')->user();
        if (!$user) {
            return false;
        }

        $accessControl = $user->accessControl;
        if (!$accessControl) {
            // Default to true if no access control exists (backward compatibility)
            return true;
        }

        $accessKey = 'access_' . $navItem;
        return $accessControl->$accessKey ?? true;
    }

    /**
     * Check if staff can perform a specific action
     */
    protected function can($action)
    {
        $user = Auth::guard('staff')->user();
        if (!$user) {
            return false;
        }

        $accessControl = $user->accessControl;
        if (!$accessControl) {
            // Default to true if no access control exists (backward compatibility)
            return true;
        }

        $actionKey = 'can_' . $action;
        return $accessControl->$actionKey ?? true;
    }

    /**
     * Redirect if staff doesn't have access to a navigation item
     */
    protected function requireNavAccess($navItem, $redirectRoute = 'staff-dashboard')
    {
        if (!$this->hasNavAccess($navItem)) {
            return redirect()->route($redirectRoute)
                ->withErrors(['error' => 'You do not have access to this page.']);
        }
        return null;
    }

    /**
     * Redirect if staff cannot perform a specific action
     */
    protected function requireCan($action, $redirectRoute = 'staff-dashboard', $errorMessage = 'You do not have permission to perform this action.')
    {
        if (!$this->can($action)) {
            return redirect()->route($redirectRoute)
                ->withErrors(['error' => $errorMessage]);
        }
        return null;
    }
}

