<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NotificationController;

// Endpoint Notifikasi
Route::post('/notifications', [NotificationController::class, 'store']);
Route::get('/notifications/user/{userId}', [NotificationController::class, 'getByUser']);
Route::put('/notifications/{id}', [NotificationController::class, 'markAsRead']);