<?php

namespace App\Events;

use App\Models\Appointment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AppointmentCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $appointment;
    public $createdBy;

    /**
     * Create a new event instance.
     */
    public function __construct(Appointment $appointment, $createdBy = null)
    {
        $this->appointment = $appointment->load(['patient.info', 'service']);
        $this->createdBy = $createdBy ?? auth()->id();
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
        return 'appointment.created';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->appointment->id,
            'appointment' => $this->formatAppointment($this->appointment),
            'created_by' => $this->createdBy,
            'timestamp' => now('Asia/Manila')->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Ensure appointment data matches calendar expectations.
     */
    protected function formatAppointment(Appointment $appointment): array
    {
        $appointment->loadMissing(['patient.info', 'service']);

        $data = $appointment->toArray();

        $start = $appointment->start_datetime
            ? $appointment->start_datetime->copy()->timezone('Asia/Manila')
            : null;
        $end = $appointment->end_datetime
            ? $appointment->end_datetime->copy()->timezone('Asia/Manila')
            : null;

        $data['start_datetime'] = $start ? $start->format('Y-m-d H:i:s') : null;
        $data['end_datetime'] = $end ? $end->format('Y-m-d H:i:s') : null;
        $data['status'] = $appointment->status ?? 'Pending';

        if (!$appointment->service && $appointment->service_id) {
            $service = \App\Models\Service::find($appointment->service_id);
            if ($service) {
                $data['service'] = [
                    'id' => $service->id,
                    'service_name' => $service->service_name,
                    'default_duration_minutes' => $service->default_duration_minutes,
                    'description' => $service->description,
                    'price' => $service->price,
                ];
            }
        }

        return $data;
    }
}

