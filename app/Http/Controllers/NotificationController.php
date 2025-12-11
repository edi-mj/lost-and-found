<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        $userId = session('user.id') ?? 1;

        $notifications = $this->notificationService->getNotifications($userId);

        return view('notifications.index', compact('notifications'));
    }

    public function getRecent()
    {
        $userId = session('user.id') ?? 1;
        $notifications = $this->notificationService->getNotifications($userId);

        // Count unread
        $unreadCount = count(array_filter($notifications, function ($n) {
            return !$n['is_read'];
        }));

        $recent = array_slice($notifications, 0, 5);

        return response()->json([
            'recent' => $recent,
            'unread_count' => $unreadCount
        ]);
    }

    public function markAsRead($id)
    {
        $this->notificationService->markAsRead($id);
        return back()->with('success', 'Notification marked as read');
    }
}
