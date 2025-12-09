<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Jobs\ProcessNotification; // Panggil Job
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // POST: Kirim Notifikasi 
    public function store(Request $request)
    {
        // Validasi
        $validated = $request->validate([
            'user_id' => 'required',
            'message' => 'required',
            'report_id' => 'nullable'
        ]);

        // Dispatch ke Job (Queue)
        ProcessNotification::dispatch($validated);

        return response()->json([
            'message' => 'Notification queued successfully'
        ], 200);
    }

    // GET: Ambil Notifikasi User
    public function getByUser($userId)
    {
        $notifications = Notification::where('user_id', $userId)
                                     ->orderBy('created_at', 'desc')
                                     ->get();

        return response()->json($notifications, 200);
    }

    // PUT: Update Status Baca
    public function markAsRead($id)
    {
        $notification = Notification::find($id);

        if (!$notification) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $notification->update(['is_read' => true]);

        return response()->json([
            'message' => 'Marked as read',
            'data' => $notification
        ], 200);
    }
}