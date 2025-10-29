<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Models\AppointmentRequest;

class NotificationComposer
{
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        // Get count of pending appointment requests
        $pendingRequestsCount = AppointmentRequest::where('status', 'Pending')->count();

        $view->with('pendingRequestsCount', $pendingRequestsCount);
    }
}

