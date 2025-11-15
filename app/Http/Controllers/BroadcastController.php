<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class BroadcastController extends Controller
{
    /**
     * Get broadcast events (for polling or SSE)
     */
    public function events(Request $request)
    {
        // Check if this is a Server-Sent Events request
        if ($request->header('Accept') === 'text/event-stream') {
            return $this->streamEvents($request);
        }

        // Otherwise, return JSON (for polling)
        return $this->getEvents($request);
    }

    /**
     * Stream events using Server-Sent Events
     */
    private function streamEvents(Request $request)
    {
        return response()->stream(function () use ($request) {
            $lastEventId = $request->get('last_event_id');
            $timeout = 30; // 30 seconds timeout
            $startTime = time();

            while (true) {
                // Check timeout
                if (time() - $startTime > $timeout) {
                    echo "event: heartbeat\n";
                    echo "data: " . json_encode(['type' => 'heartbeat']) . "\n\n";
                    ob_flush();
                    flush();
                    $startTime = time();
                    continue;
                }

                // Get new events from cache
                $events = $this->getNewEvents($lastEventId);
                
                foreach ($events as $event) {
                    echo "event: {$event['event']}\n";
                    echo "data: " . json_encode($event['data']) . "\n\n";
                    ob_flush();
                    flush();
                    $lastEventId = $event['id'];
                }

                // Sleep for 1 second before checking again
                sleep(1);
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    /**
     * Get events as JSON (for polling)
     */
    private function getEvents(Request $request): JsonResponse
    {
        $lastEventId = $request->get('last_event_id');
        $events = $this->getNewEvents($lastEventId);

        return response()->json([
            'success' => true,
            'events' => $events,
            'last_event_id' => $events ? end($events)['id'] : $lastEventId,
        ]);
    }

    /**
     * Get new events since last event ID
     */
    private function getNewEvents(?string $lastEventId): array
    {
        $cacheKey = 'broadcast_events';
        $allEvents = Cache::get($cacheKey, []);

        if (!$lastEventId) {
            // Return only the last 10 events
            return array_slice($allEvents, -10);
        }

        // Find events after the last event ID
        $found = false;
        $newEvents = [];

        foreach ($allEvents as $event) {
            if ($found) {
                $newEvents[] = $event;
            }
            if ($event['id'] === $lastEventId) {
                $found = true;
            }
        }

        return $newEvents;
    }

    /**
     * Store a broadcast event (called by event listeners)
     */
    public static function storeEvent(string $eventType, array $data): void
    {
        $cacheKey = 'broadcast_events';
        $events = Cache::get($cacheKey, []);
        
        $event = [
            'id' => uniqid('evt_', true),
            'event' => $eventType,
            'data' => $data,
            'timestamp' => now()->toIso8601String(),
        ];

        $events[] = $event;

        // Keep only last 100 events
        if (count($events) > 100) {
            $events = array_slice($events, -100);
        }

        Cache::put($cacheKey, $events, now()->addHours(24));
    }
}

