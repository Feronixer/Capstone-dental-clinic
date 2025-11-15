<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AppointmentDeleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $appointmentId;
    public $deletedBy;

    /**
     * Create a new event instance.
     */
    public function __construct(int $appointmentId, $deletedBy = null)
    {
        $this->appointmentId = $appointmentId;
        $this->deletedBy = $deletedBy ?? auth()->id();
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('appointments'),
            new PrivateChannel('staff'),
            new PrivateChannel('admin'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'appointment.deleted';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->appointmentId,
            'deleted_by' => $this->deletedBy,
            'timestamp' => now('Asia/Manila')->format('Y-m-d H:i:s'),
        ];
    }
}

