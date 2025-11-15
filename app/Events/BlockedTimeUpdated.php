<?php

namespace App\Events;

use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BlockedTimeUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $blockedTime;
    public $action;

    /**
     * Create a new event instance.
     */
    public function __construct($blockedTime, string $action = 'updated')
    {
        $this->blockedTime = $blockedTime;
        $this->action = $action;
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
        return 'blocked-time.updated';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'blocked_time' => $this->formatBlockedTime($this->blockedTime),
            'action' => $this->action,
            'timestamp' => now('Asia/Manila')->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Normalize blocked time data for the calendar.
     */
    protected function formatBlockedTime($blockedTime): array
    {
        if (is_array($blockedTime)) {
            $data = $blockedTime;
        } elseif ($blockedTime instanceof \App\Models\BlockedTime) {
            $data = $blockedTime->toArray();
        } else {
            return (array) $blockedTime;
        }

        $start = isset($data['start_datetime']) ? Carbon::parse($data['start_datetime'], 'Asia/Manila') : null;
        $end = isset($data['end_datetime']) ? Carbon::parse($data['end_datetime'], 'Asia/Manila') : null;

        $data['start_datetime'] = $start ? $start->format('Y-m-d H:i:s') : null;
        $data['end_datetime'] = $end ? $end->format('Y-m-d H:i:s') : null;

        return $data;
    }
}

