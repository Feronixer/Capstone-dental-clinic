<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StaffAccessControlUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $staffId;
    public $accessControl;
    public $updatedBy;

    /**
     * Create a new event instance.
     */
    public function __construct($staffId, $accessControl, $updatedBy = null)
    {
        $this->staffId = $staffId;
        $this->accessControl = $accessControl;
        $this->updatedBy = $updatedBy;
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'staff_id' => $this->staffId,
            'access_control' => $this->accessControl->toArray(),
            'updated_by' => $this->updatedBy,
            'timestamp' => now('Asia/Manila')->format('Y-m-d H:i:s'),
        ];
    }
}

