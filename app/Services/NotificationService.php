<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = env('API_NOTIFICATION');
    }

    public function getNotifications($userId)
    {
        try {
            $response = Http::get("{$this->baseUrl}/notifications", [
                'user_id' => $userId
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Failed to fetch notifications: ' . $response->body());
            return [];
        } catch (\Exception $e) {
            Log::error('Exception fetching notifications: ' . $e->getMessage());
            return [];
        }
    }

    public function sendNotification($data)
    {
        try {
            $response = Http::post("{$this->baseUrl}/notifications/send", $data);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Exception sending notification: ' . $e->getMessage());
            return false;
        }
    }

    public function markAsRead($id)
    {
        try {
            $response = Http::patch("{$this->baseUrl}/notifications/{$id}/read");
            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Exception marking notification as read: ' . $e->getMessage());
            return false;
        }
    }
}
