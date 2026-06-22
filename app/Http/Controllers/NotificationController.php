<?php

namespace App\Http\Controllers;

use App\Models\HotelNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Strip HTML tags and decode HTML entities from a notification body.
     */
    private function cleanBody(string $body): string
    {
        // Decode HTML entities first (e.g. &lt; → <), then strip tags
        return html_entity_decode(strip_tags($body), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     * Apply cleanBody to a collection of notifications.
     */
    private function cleanCollection($notifications): \Illuminate\Support\Collection
    {
        return $notifications->map(function ($n) {
            $n->body = $this->cleanBody($n->body ?? '');
            return $n;
        });
    }

    /**
     * Get paginated notifications, latest first (admin).
     */
    public function index(): JsonResponse
    {
        $notifications = HotelNotification::orderByDesc('created_at')
            ->limit(50)
            ->get();

        return response()->json($this->cleanCollection($notifications));
    }

    /**
     * Public endpoint: fetch notifications for specific reservation IDs.
     * Accepts ?reservation_ids=1,2,3
     */
    public function publicIndex(Request $request): JsonResponse
    {
        $ids = $request->query('reservation_ids', '');
        if (!$ids) {
            return response()->json([]);
        }

        $reservationIds = array_filter(
            array_map('intval', explode(',', $ids)),
            fn($id) => $id > 0
        );

        if (empty($reservationIds)) {
            return response()->json([]);
        }

        $notifications = HotelNotification::whereIn('reservation_id', $reservationIds)
            ->orderByDesc('created_at')
            ->limit(30)
            ->get();

        return response()->json($this->cleanCollection($notifications));
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllRead(): JsonResponse
    {
        HotelNotification::where('is_read', false)->update(['is_read' => true]);
        return response()->json(['message' => 'All notifications marked as read.']);
    }

    /**
     * Mark a single notification as read.
     */
    public function markRead(int $id): JsonResponse
    {
        $notification = HotelNotification::findOrFail($id);
        $notification->update(['is_read' => true]);
        $notification->body = $this->cleanBody($notification->body ?? '');
        return response()->json($notification);
    }

    /**
     * Count unread notifications.
     */
    public function unreadCount(): JsonResponse
    {
        $count = HotelNotification::where('is_read', false)->count();
        return response()->json(['count' => $count]);
    }
}
