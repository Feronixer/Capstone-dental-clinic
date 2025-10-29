<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    /**
     * Display a listing of notifications
     */
    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $unreadCount = Notification::where('user_id', auth()->id())
            ->unread()
            ->count();

        return view('patient.notifications', compact('notifications', 'unreadCount'));
    }

    /**
     * Get notifications for dropdown (AJAX)
     */
    public function getRecent(): JsonResponse
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'icon_class' => $notification->icon_class,
                    'icon_color' => $notification->icon_color,
                    'is_read' => $notification->is_read,
                    'time_ago' => $notification->time_ago,
                    'created_at' => $notification->created_at->toISOString(),
                    'data' => $notification->data,
                ];
            });

        $unreadCount = Notification::where('user_id', auth()->id())
            ->unread()
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Get unread count
     */
    public function getUnreadCount(): JsonResponse
    {
        $count = Notification::where('user_id', auth()->id())
            ->unread()
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id): JsonResponse
    {
        $notification = Notification::where('user_id', auth()->id())
            ->findOrFail($id);

        $notification->markAsRead();

        $unreadCount = Notification::where('user_id', auth()->id())
            ->unread()
            ->count();

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read',
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Mark notification as unread
     */
    public function markAsUnread($id): JsonResponse
    {
        $notification = Notification::where('user_id', auth()->id())
            ->findOrFail($id);

        $notification->markAsUnread();

        $unreadCount = Notification::where('user_id', auth()->id())
            ->unread()
            ->count();

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as unread',
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(): JsonResponse
    {
        Notification::where('user_id', auth()->id())
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read',
            'unread_count' => 0,
        ]);
    }

    /**
     * Delete a notification
     */
    public function destroy($id): JsonResponse
    {
        $notification = Notification::where('user_id', auth()->id())
            ->findOrFail($id);

        $notification->delete();

        $unreadCount = Notification::where('user_id', auth()->id())
            ->unread()
            ->count();

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted',
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Delete all read notifications
     */
    public function clearRead(): JsonResponse
    {
        Notification::where('user_id', auth()->id())
            ->read()
            ->delete();

        $unreadCount = Notification::where('user_id', auth()->id())
            ->unread()
            ->count();

        return response()->json([
            'success' => true,
            'message' => 'Read notifications cleared',
            'unread_count' => $unreadCount,
        ]);
    }
}
